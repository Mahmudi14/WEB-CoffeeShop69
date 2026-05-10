<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveTaxSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'price_includes_tax' => ['nullable', 'boolean'],
        ];
    }

    public function validatedData(): array
    {
        $validated = $this->validated();

        $validated['is_active'] = $this->boolean('is_active');
        $validated['price_includes_tax'] = $this->boolean('price_includes_tax');

        return $validated;
    }
}