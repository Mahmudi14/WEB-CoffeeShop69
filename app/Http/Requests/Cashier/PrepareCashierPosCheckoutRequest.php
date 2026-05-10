<?php

namespace App\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class PrepareCashierPosCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'cart_json' => ['required', 'string'],
        ];
    }
}