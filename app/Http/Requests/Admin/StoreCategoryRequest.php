<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}