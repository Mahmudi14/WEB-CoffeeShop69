<?php

namespace App\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $rawAmount = $this->input('amount');

        $normalizedAmount = $rawAmount === null
            ? null
            : preg_replace('/\D/', '', (string) $rawAmount);

        $this->merge([
            'amount' => $normalizedAmount === '' ? null : (int) $normalizedAmount,
        ]);
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}