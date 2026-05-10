<?php

use App\Http\Controllers\Api\PrintJobApiController;
use App\Http\Middleware\EnsurePrinterApiKeyIsValid;
use Illuminate\Support\Facades\Route;

Route::middleware(EnsurePrinterApiKeyIsValid::class)
    ->prefix('printer/print-jobs')
    ->name('printer.print-jobs.')
    ->controller(PrintJobApiController::class)
    ->group(function () {
        Route::get('/pending', 'pending')->name('pending');
        Route::post('/{printJob}/printed', 'printed')->name('printed');
        Route::post('/{printJob}/failed', 'failed')->name('failed');
        Route::post('/{printJob}/retry', 'retry')->name('retry');
    });