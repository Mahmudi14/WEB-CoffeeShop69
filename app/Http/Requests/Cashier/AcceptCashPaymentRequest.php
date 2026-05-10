<?php

namespace App\Http\Requests\Cashier;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class AcceptCashPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $rawAmount = $this->input('paid_amount');

        $normalizedAmount = preg_replace('/\D/', '', (string) $rawAmount);

        $this->merge([
            'paid_amount' => $normalizedAmount === '' ? null : (int) $normalizedAmount,
        ]);
    }

    public function rules(): array
    {
        $order = $this->route('order');

        $minimumPaidAmount = $order instanceof Order
            ? (int) $order->grand_total
            : 1;

        return [
            'paid_amount' => ['required', 'integer', 'min:' . $minimumPaidAmount],
        ];
    }
}