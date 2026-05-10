<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->input('code'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', 'unique:cafe_tables,code'],
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