<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CancelAdminOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'cancel_reason' => ['required', 'string', 'max:1000'],
        ];
    }
}