<?php

namespace App\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCashierCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'integer', 'min:0'],
        ];
    }
}