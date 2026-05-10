<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCashierRequest extends FormRequest
{
    public function authorize(): bool
    {
        $cashier = $this->route('cashier');

        return $this->user() !== null
            && $this->user()->hasRole('admin')
            && $cashier instanceof User
            && $cashier->hasRole('cashier');
    }

    public function rules(): array
    {
        /** @var User $cashier */
        $cashier = $this->route('cashier');

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($cashier->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}