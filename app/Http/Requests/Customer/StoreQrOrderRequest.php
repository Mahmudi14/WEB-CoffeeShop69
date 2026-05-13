<?php

namespace App\Http\Requests\Customer;

use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQrOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:100'],
            'payment_method' => [
                'required',
                Rule::in([
                    Payment::METHOD_CASH,
                    Payment::METHOD_QRIS,
                    Payment::METHOD_TRANSFER,
                ]),
            ],
            'proof' => [
                Rule::requiredIf(fn () => in_array($this->input('payment_method'), [
                    Payment::METHOD_QRIS,
                    Payment::METHOD_TRANSFER,
                ], true)),
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:2048',
            ],
            'customer_note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'proof.required' => 'Bukti pembayaran wajib diupload untuk QRIS/Transfer.',
        ];
    }
}