<?php

namespace App\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class CloseCashierShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'closing_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}