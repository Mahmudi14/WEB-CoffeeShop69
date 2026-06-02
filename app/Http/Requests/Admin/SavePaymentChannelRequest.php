<?php

namespace App\Http\Requests\Admin;

use App\Models\PaymentChannel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SavePaymentChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'method' => [
                'required',
                Rule::in([
                    PaymentChannel::METHOD_QRIS,
                    PaymentChannel::METHOD_TRANSFER,
                    PaymentChannel::METHOD_EWALLET,
                ]),
            ],
            'name' => ['required', 'string', 'max:100'],
            'account_name' => ['nullable', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'qr_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'remove_qr_image' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (
                in_array($this->input('method'), [
                    PaymentChannel::METHOD_TRANSFER,
                    PaymentChannel::METHOD_EWALLET,
                ], true)
                && blank($this->input('account_number'))
            ) {
                $validator->errors()->add(
                    'account_number',
                    'Nomor rekening atau nomor e-wallet wajib diisi.'
                );
            }
        });
    }

    public function validatedData(): array
    {
        $validated = $this->validated();

        $validated['is_active'] = $this->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        unset($validated['qr_image'], $validated['remove_qr_image']);

        return $validated;
    }

    public function shouldRemoveQrImage(): bool
    {
        return $this->boolean('remove_qr_image');
    }
}