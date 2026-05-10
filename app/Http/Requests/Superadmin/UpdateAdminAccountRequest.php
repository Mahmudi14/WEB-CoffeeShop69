<?php

namespace App\Http\Requests\Superadmin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAdminAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        $admin = $this->route('admin');

        return $this->user() !== null
            && $this->user()->hasRole('superadmin')
            && $admin instanceof User
            && $admin->hasRole('admin');
    }

    public function rules(): array
    {
        /** @var User $admin */
        $admin = $this->route('admin');

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($admin->id),
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