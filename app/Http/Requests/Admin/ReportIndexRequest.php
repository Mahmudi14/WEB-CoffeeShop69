<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $currentYear = now()->year;

        return [
            'periode' => [
                'nullable',
                Rule::in(['harian', 'bulanan', 'tahunan']),
            ],
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun' => [
                'nullable',
                'integer',
                'min:' . ($currentYear - 10),
                'max:' . $currentYear,
            ],
            'kasir' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function filters(): array
    {
        $validated = $this->validated();

        return [
            'periode' => $validated['periode'] ?? 'harian',
            'bulan' => (int) ($validated['bulan'] ?? now()->month),
            'tahun' => (int) ($validated['tahun'] ?? now()->year),
            'kasir' => (string) ($validated['kasir'] ?? 'all'),
        ];
    }
}