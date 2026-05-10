<?php

namespace App\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class RejectPaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:500'],
        ];
    }
}