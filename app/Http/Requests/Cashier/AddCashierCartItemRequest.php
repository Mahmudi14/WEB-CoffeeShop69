<?php

namespace App\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class AddCashierCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'menu_id' => ['required', 'exists:menus,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}