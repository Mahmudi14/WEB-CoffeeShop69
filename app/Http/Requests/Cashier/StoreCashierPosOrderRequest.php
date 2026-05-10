<?php

namespace App\Http\Requests\Cashier;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCashierPosOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $rawPaidAmount = $this->input('paid_amount');

        $normalizedPaidAmount = preg_replace('/\D/', '', (string) $rawPaidAmount);

        $this->merge([
            'paid_amount' => $normalizedPaidAmount === '' ? null : (int) $normalizedPaidAmount,
        ]);
    }

    public function rules(): array
    {
        return [
            'order_submit_token' => ['required', 'string'],
            'customer_name' => ['required', 'string', 'max:100'],
            'order_type' => [
                'required',
                Rule::in([
                    Order::TYPE_DINE_IN,
                    Order::TYPE_TAKEAWAY,
                ]),
            ],
            'table_id' => [
                Rule::requiredIf(fn () => $this->input('order_type') === Order::TYPE_DINE_IN),
                'nullable',
                'exists:cafe_tables,id',
            ],
            'payment_method' => [
                'required',
                Rule::in([
                    Payment::METHOD_CASH,
                    Payment::METHOD_QRIS,
                    Payment::METHOD_TRANSFER,
                ]),
            ],
            'paid_amount' => ['nullable', 'integer', 'min:0'],
            'payment_note' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'table_id.required' => 'Meja wajib dipilih untuk order dine-in.',
        ];
    }
}