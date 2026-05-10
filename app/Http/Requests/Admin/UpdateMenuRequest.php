<?php

namespace App\Http\Requests\Admin;

use App\Models\Menu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMenuRequest extends FormRequest
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
        /** @var Menu $menu */
        $menu = $this->route('menu');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('menus', 'name')
                    ->whereNull('deleted_at')
                    ->ignore($menu->id),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'normal_price' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }
}