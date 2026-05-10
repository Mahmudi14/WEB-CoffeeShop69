<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MarkPrintJobFailedRequest;
use App\Http\Resources\PrintJobResource;
use App\Models\PrintJob;
use App\Services\Printer\PrintJobQueueService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PrintJobApiController extends Controller
{
    public function __construct(
        private readonly PrintJobQueueService $printJobQueueService
    ) {
    }

    public function pending()
    {
        $printJob = $this->printJobQueueService->claimNextPendingJob();

        if (! $printJob) {
            return response()->noContent();
        }

        return response()->json(
            new PrintJobResource($printJob)
        );
    }

    public function printed(Request $request, PrintJob $printJob)
    {
        try {
            $printJob = $this->printJobQueueService->markAsPrinted(
                printJob: $printJob,
                type: $request->input('type')
            );
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => collect($exception->errors())->flatten()->first(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Print job berhasil ditandai printed.',
            'job' => new PrintJobResource($printJob),
        ]);
    }

    public function failed(MarkPrintJobFailedRequest $request, PrintJob $printJob)
    {
        try {
            $printJob = $this->printJobQueueService->markAsFailed(
                printJob: $printJob,
                errorMessage: $request->errorMessage(),
                type: $request->validated('type')
            );
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => collect($exception->errors())->flatten()->first(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Print job berhasil ditandai failed.',
            'job' => new PrintJobResource($printJob),
        ]);
    }

    public function retry(Request $request, PrintJob $printJob)
    {
        try {
            $printJob = $this->printJobQueueService->retry(
                printJob: $printJob,
                type: $request->input('type')
            );
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => collect($exception->errors())->flatten()->first(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Print job dikembalikan ke antrean.',
            'job' => new PrintJobResource($printJob),
        ]);
    }
}