<?php

namespace App\Http\Requests\Admin;

use App\Models\CafeTable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTableRequest extends FormRequest
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
        /** @var CafeTable $table */
        $table = $this->route('table');

        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('cafe_tables', 'code')->ignore($table->id),
            ],
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