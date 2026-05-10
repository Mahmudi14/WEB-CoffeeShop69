<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('normal_price')) {
            $rawPrice = $this->input('normal_price');
            $normalizedPrice = preg_replace('/\D/', '', (string) $rawPrice);

            $this->merge([
                'normal_price' => $normalizedPrice === '' ? null : (int) $normalizedPrice,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('menus', 'name')->whereNull('deleted_at'),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'normal_price' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }
}