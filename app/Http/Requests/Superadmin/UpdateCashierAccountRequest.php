<?php

namespace App\Http\Requests\Superadmin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateCashierAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('superadmin');
    }

    public function rules(): array
    {
        /** @var User $cashier */
        $cashier = $this->route('cashier');

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($cashier->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function validatedData(): array
    {
        $validated = $this->validated();

        $validated['is_active'] = $this->boolean('is_active');

        return $validated;
    }
}