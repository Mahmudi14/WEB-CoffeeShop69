<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePrinterApiKeyIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = (string) config('services.printer.api_key');
        $submittedKey = (string) $request->header('X-PRINTER-API-KEY');

        if (
            $expectedKey === '' ||
            $submittedKey === '' ||
            ! hash_equals($expectedKey, $submittedKey)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'API key printer salah atau tidak dikirim.',
            ], 401);
        }

        return $next($request);
    }
}