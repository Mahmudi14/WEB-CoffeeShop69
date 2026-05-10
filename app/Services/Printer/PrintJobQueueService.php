<?php

namespace App\Services\Printer;

use App\Models\PrintJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PrintJobQueueService
{
    public function claimNextPendingJob(): ?PrintJob
    {
        return DB::transaction(function () {
            $job = PrintJob::query()
                ->where(function ($query) {
                    $query
                        ->where('status', PrintJob::STATUS_PENDING)
                        ->orWhere(function ($staleQuery) {
                            $staleQuery
                                ->where('status', PrintJob::STATUS_PRINTING)
                                ->where('updated_at', '<=', now()->subMinutes(
                                    (int) config('services.printer.stale_after_minutes', 2)
                                ));
                        });
                })
                ->where('attempts', '<', (int) config('services.printer.max_attempts', 5))
                ->orderBy('created_at')
                ->lockForUpdate()
                ->first();

            if (! $job) {
                return null;
            }

            $job->update([
                'status' => PrintJob::STATUS_PRINTING,
                'attempts' => $job->attempts + 1,
                'error_message' => null,
                'failed_at' => null,
            ]);

            return $job->fresh();
        });
    }

    public function markAsPrinted(PrintJob $printJob, ?string $type = null): PrintJob
    {
        $this->ensureTypeMatches($printJob, $type);

        if ($printJob->status !== PrintJob::STATUS_PRINTED) {
            $printJob->update([
                'status' => PrintJob::STATUS_PRINTED,
                'printed_at' => now(),
                'failed_at' => null,
                'error_message' => null,
            ]);
        }

        return $printJob->fresh();
    }

    public function markAsFailed(PrintJob $printJob, string $errorMessage, ?string $type = null): PrintJob
    {
        $this->ensureTypeMatches($printJob, $type);

        $printJob->update([
            'status' => PrintJob::STATUS_FAILED,
            'failed_at' => now(),
            'error_message' => $errorMessage,
        ]);

        return $printJob->fresh();
    }

    public function retry(PrintJob $printJob, ?string $type = null): PrintJob
    {
        $this->ensureTypeMatches($printJob, $type);

        if ($printJob->status !== PrintJob::STATUS_FAILED) {
            throw ValidationException::withMessages([
                'print_job' => 'Hanya print job failed yang bisa diulang.',
            ]);
        }

        $printJob->update([
            'status' => PrintJob::STATUS_PENDING,
            'failed_at' => null,
            'error_message' => null,
        ]);

        return $printJob->fresh();
    }

    private function ensureTypeMatches(PrintJob $printJob, ?string $type): void
    {
        if ($type && $type !== $printJob->type) {
            throw ValidationException::withMessages([
                'type' => 'Tipe print job tidak sesuai.',
            ]);
        }
    }
}