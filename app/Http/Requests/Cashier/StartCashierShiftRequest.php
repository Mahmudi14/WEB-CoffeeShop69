<?php

namespace App\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class StartCashierShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $rawOpeningCash = $this->input('opening_cash');

        $normalizedOpeningCash = preg_replace('/\D/', '', (string) $rawOpeningCash);

        $this->merge([
            'opening_cash' => $normalizedOpeningCash === '' ? null : (int) $normalizedOpeningCash,
        ]);
    }

    public function rules(): array
    {
        return [
            'opening_cash' => ['required', 'integer', 'min:0'],
            'opening_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}