<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MarkPrintJobFailedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'error' => ['nullable', 'string', 'max:1000'],
            'error_message' => ['nullable', 'string', 'max:1000'],
            'type' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function errorMessage(): string
    {
        return $this->validated('error')
            ?? $this->validated('error_message')
            ?? 'Gagal mencetak.';
    }
}