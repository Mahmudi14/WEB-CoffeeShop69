<?php

namespace App\Services;

use App\Models\OrderNumberSequence;
use Illuminate\Database\QueryException;

class OrderNumberService
{
    public function generate(): string
    {
        $today = now()->toDateString();

        try {
            OrderNumberSequence::query()->firstOrCreate(
                ['sequence_date' => $today],
                ['last_number' => 0]
            );
        } catch (QueryException $exception) {
            // Jika dua request bersamaan membuat sequence tanggal yang sama,
            // unique index akan menahan duplikasi. Setelah itu kita ambil row-nya.
        }

        $sequence = OrderNumberSequence::query()
            ->where('sequence_date', $today)
            ->lockForUpdate()
            ->firstOrFail();

        $sequence->last_number++;
        $sequence->save();

        return 'ORD-' . now()->format('Ymd') . '-' . str_pad((string) $sequence->last_number, 4, '0', STR_PAD_LEFT);
    }
}