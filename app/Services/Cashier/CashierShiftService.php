<?php

namespace App\Services\Cashier;

use App\Models\CashierShift;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CashierShiftService
{
    public function __construct(
        private readonly CashierShiftSummaryService $summaryService,
        private readonly CashierShiftPrintService $printService
    ) {
    }

    public function getActiveForUser(User|int|null $user): ?CashierShift
    {
        if (! $user) {
            return null;
        }

        $userId = $user instanceof User ? $user->id : $user;

        return CashierShift::query()
            ->where('user_id', $userId)
            ->where('status', CashierShift::STATUS_OPEN)
            ->first();
    }

    public function getAnyActiveShift(): ?CashierShift
    {
        return CashierShift::query()
            ->with('user')
            ->where('status', CashierShift::STATUS_OPEN)
            ->first();
    }

    public function start(User $cashier, array $data): CashierShift
    {
        return DB::transaction(function () use ($cashier, $data) {
            $activeShift = CashierShift::query()
                ->with('user')
                ->where('status', CashierShift::STATUS_OPEN)
                ->lockForUpdate()
                ->first();

            if ($activeShift) {
                if ((int) $activeShift->user_id === (int) $cashier->id) {
                    throw ValidationException::withMessages([
                        'shift' => 'Kamu masih punya shift aktif.',
                    ]);
                }

                throw ValidationException::withMessages([
                    'shift' => 'Shift belum bisa dimulai. Saat ini shift masih aktif oleh kasir ' . ($activeShift->user?->name ?? 'lain') . '.',
                ]);
            }

            return CashierShift::create([
                'user_id' => $cashier->id,
                'opening_cash' => $data['opening_cash'],
                'opened_at' => now(),
                'status' => CashierShift::STATUS_OPEN,
                'opening_note' => $data['opening_note'] ?? null,
            ]);
        });
    }

    public function close(CashierShift $shift, User $cashier, array $data): void
    {
        $unfinishedOrdersCount = $this->summaryService->unfinishedOrdersCount($shift);

        if ($unfinishedOrdersCount > 0) {
            throw ValidationException::withMessages([
                'shift' => "Shift belum bisa ditutup. Masih ada {$unfinishedOrdersCount} pesanan aktif yang belum diselesaikan.",
            ]);
        }

        DB::transaction(function () use ($shift, $cashier, $data) {
            $shift->update([
                'status' => CashierShift::STATUS_CLOSED,
                'closed_at' => now(),
                'closing_note' => $data['closing_note'] ?? null,
            ]);

            $this->printService->queueClosingPrintIfMissing($shift, $cashier);
        });
    }
}