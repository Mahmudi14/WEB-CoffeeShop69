<?php

namespace App\Http\Requests\Admin;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminOrderIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'order_status' => [
                'nullable',
                Rule::in([
                    Order::STATUS_PENDING_PAYMENT,
                    Order::STATUS_PENDING_PAYMENT_VERIFICATION,
                    Order::STATUS_PROCESSING,
                    Order::STATUS_COMPLETED,
                    Order::STATUS_CANCELLED,
                    Order::STATUS_REJECTED,
                    Order::STATUS_EXPIRED,
                ]),
            ],
            'payment_status' => [
                'nullable',
                Rule::in([
                    Order::PAYMENT_UNPAID,
                    Order::PAYMENT_PENDING_VERIFICATION,
                    Order::PAYMENT_PAID,
                    Order::PAYMENT_REJECTED,
                    Order::PAYMENT_VOIDED,
                ]),
            ],
            'source' => [
                'nullable',
                Rule::in([
                    Order::SOURCE_CUSTOMER_QR,
                    Order::SOURCE_CASHIER_POS,
                ]),
            ],
        ];
    }

    public function filters(): array
    {
        return [
            'search' => trim((string) $this->query('search')),
            'date_from' => $this->query('date_from'),
            'date_to' => $this->query('date_to'),
            'order_status' => $this->query('order_status'),
            'payment_status' => $this->query('payment_status'),
            'source' => $this->query('source'),
        ];
    }
}