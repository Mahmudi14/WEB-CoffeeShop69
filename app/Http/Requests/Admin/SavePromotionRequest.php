<?php

namespace App\Http\Requests\Admin;

use App\Models\Promotion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SavePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],

            'scope' => [
                'required',
                Rule::in([
                    Promotion::SCOPE_ALL_MENU,
                    Promotion::SCOPE_SELECTED_MENU,
                ]),
            ],

            'discount_type' => [
                'required',
                Rule::in([
                    Promotion::DISCOUNT_PERCENTAGE,
                    Promotion::DISCOUNT_FIXED,
                ]),
            ],

            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'priority' => ['required', 'integer', 'min:1'],

            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],

            'is_active' => ['nullable', 'boolean'],

            'menu_ids' => [
                Rule::requiredIf(fn () => $this->input('scope') === Promotion::SCOPE_SELECTED_MENU),
                'array',
            ],
            'menu_ids.*' => ['integer', 'exists:menus,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (
                $this->input('discount_type') === Promotion::DISCOUNT_PERCENTAGE
                && (float) $this->input('discount_value') > 100
            ) {
                $validator->errors()->add(
                    'discount_value',
                    'Diskon persentase tidak boleh lebih dari 100%.'
                );
            }

            if (
                $this->input('scope') === Promotion::SCOPE_SELECTED_MENU
                && count((array) $this->input('menu_ids', [])) === 0
            ) {
                $validator->errors()->add(
                    'menu_ids',
                    'Minimal pilih satu menu untuk promo selected menu.'
                );
            }
        });
    }

    public function validatedData(): array
    {
        $validated = $this->validated();

        $validated['is_active'] = $this->boolean('is_active');

        $validated['menu_ids'] = array_values(array_unique(
            $validated['menu_ids'] ?? []
        ));

        return $validated;
    }
}