<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\ShiftExpense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AdminReportService
{
    public function reportData(array $filters): array
    {
        $periodeList = $this->periodeList();

        $periode = $filters['periode'];
        $selectedMonth = $filters['bulan'];
        $selectedYear = $filters['tahun'];
        $selectedKasir = $filters['kasir'];

        $daftarKasir = $this->cashierOptions();

        if (! $daftarKasir->contains(fn ($kasir) => (string) $kasir['id'] === $selectedKasir)) {
            $selectedKasir = 'all';
        }

        $selectedKasirName = $daftarKasir
            ->first(fn ($kasir) => (string) $kasir['id'] === $selectedKasir)['nama']
            ?? 'Semua Kasir';

        [$startDate, $endDate, $periodeInfo, $chartTitle, $chartRows] = $this->buildPeriodData(
            periode: $periode,
            selectedMonth: $selectedMonth,
            selectedYear: $selectedYear,
            selectedKasir: $selectedKasir
        );

        $totalRevenue = (int) $this->paidOrderQuery($startDate, $endDate, $selectedKasir)
            ->sum('grand_total');

        $totalOrder = (int) $this->paidOrderQuery($startDate, $endDate, $selectedKasir)
            ->count();

        $totalDiscount = (int) $this->paidOrderQuery($startDate, $endDate, $selectedKasir)
            ->sum('discount_total');

        $totalTax = (int) $this->paidOrderQuery($startDate, $endDate, $selectedKasir)
            ->sum('tax_total');

        $totalExpense = $this->expenseTotal($startDate, $endDate, $selectedKasir);

        $netRevenue = $totalRevenue - $totalExpense;
        $averageOrder = $totalOrder > 0
            ? (int) round($totalRevenue / $totalOrder)
            : 0;

        $summaryCards = $this->summaryCards(
            totalRevenue: $totalRevenue,
            totalOrder: $totalOrder,
            averageOrder: $averageOrder,
            netRevenue: $netRevenue
        );

        $highest = collect($chartRows)
            ->sortByDesc('amount')
            ->first();

        $highestLabel = $highest['label'] ?? '-';
        $highestAmount = (int) ($highest['amount'] ?? 0);

        $zeroDays = collect($chartRows)
            ->filter(fn ($item) => (int) $item['amount'] === 0)
            ->count();

        $chartData = $this->chartData($chartRows);

        return [
            'periodeList' => $periodeList,
            'periode' => $periode,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'selectedKasir' => $selectedKasir,
            'selectedKasirName' => $selectedKasirName,
            'daftarKasir' => $daftarKasir,
            'periodeInfo' => $periodeInfo,
            'summaryCards' => $summaryCards,
            'chartTitle' => $chartTitle,
            'chartData' => $chartData,
            'highestLabel' => $highestLabel,
            'highestAmount' => $highestAmount,
            'zeroDays' => $zeroDays,
            'ramaiLabel' => $highestLabel,
            'ramaiAmount' => $highestAmount,
            'ramaiSubtitle' => $this->busiestSubtitle($periode),
            'totalDiscount' => $totalDiscount,
            'totalTax' => $totalTax,
            'totalExpense' => $totalExpense,
        ];
    }

    public function periodeList(): array
    {
        return [
            'harian' => 'Harian',
            'bulanan' => 'Bulanan',
            'tahunan' => 'Tahunan',
        ];
    }

    private function cashierOptions(): Collection
    {
        $cashiers = User::role('cashier')
            ->orderBy('name')
            ->get(['id', 'name']);

        return collect([
            [
                'id' => 'all',
                'nama' => 'Semua Kasir',
            ],
        ])
            ->merge(
                $cashiers->map(fn ($cashier) => [
                    'id' => (string) $cashier->id,
                    'nama' => $cashier->name,
                ])
            )
            ->values();
    }

    private function buildPeriodData(
        string $periode,
        int $selectedMonth,
        int $selectedYear,
        string $selectedKasir
    ): array {
        if ($periode === 'bulanan') {
            return $this->monthlyPeriodData($selectedYear, $selectedKasir);
        }

        if ($periode === 'tahunan') {
            return $this->yearlyPeriodData($selectedYear, $selectedKasir);
        }

        return $this->dailyPeriodData($selectedMonth, $selectedYear, $selectedKasir);
    }

    private function monthlyPeriodData(int $selectedYear, string $selectedKasir): array
    {
        $startDate = Carbon::create($selectedYear, 1, 1)->startOfDay();
        $endDate = Carbon::create($selectedYear, 12, 31)->endOfDay();

        $chartRows = [];

        for ($month = 1; $month <= 12; $month++) {
            $periodStart = Carbon::create($selectedYear, $month, 1)->startOfMonth();
            $periodEnd = Carbon::create($selectedYear, $month, 1)->endOfMonth();

            $chartRows[] = [
                'label' => $periodStart->translatedFormat('M'),
                'amount' => (int) $this->paidOrderQuery($periodStart, $periodEnd, $selectedKasir)
                    ->sum('grand_total'),
            ];
        }

        return [
            $startDate,
            $endDate,
            'Tahun ' . $selectedYear,
            'Pendapatan Bulanan',
            $chartRows,
        ];
    }

    private function yearlyPeriodData(int $selectedYear, string $selectedKasir): array
    {
        $startYear = $selectedYear - 3;
        $endYear = $selectedYear;

        $startDate = Carbon::create($startYear, 1, 1)->startOfDay();
        $endDate = Carbon::create($endYear, 12, 31)->endOfDay();

        $chartRows = [];

        for ($year = $startYear; $year <= $endYear; $year++) {
            $periodStart = Carbon::create($year, 1, 1)->startOfDay();
            $periodEnd = Carbon::create($year, 12, 31)->endOfDay();

            $chartRows[] = [
                'label' => (string) $year,
                'amount' => (int) $this->paidOrderQuery($periodStart, $periodEnd, $selectedKasir)
                    ->sum('grand_total'),
            ];
        }

        return [
            $startDate,
            $endDate,
            $startYear . ' - ' . $endYear,
            'Pendapatan Tahunan',
            $chartRows,
        ];
    }

    private function dailyPeriodData(int $selectedMonth, int $selectedYear, string $selectedKasir): array
    {
        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        $chartRows = [];

        for ($day = 1; $day <= $endDate->day; $day++) {
            $periodStart = Carbon::create($selectedYear, $selectedMonth, $day)->startOfDay();
            $periodEnd = Carbon::create($selectedYear, $selectedMonth, $day)->endOfDay();

            $chartRows[] = [
                'label' => (string) $day,
                'amount' => (int) $this->paidOrderQuery($periodStart, $periodEnd, $selectedKasir)
                    ->sum('grand_total'),
            ];
        }

        return [
            $startDate,
            $endDate,
            Carbon::create($selectedYear, $selectedMonth, 1)->translatedFormat('F Y'),
            'Pendapatan Harian',
            $chartRows,
        ];
    }

    private function paidOrderQuery(Carbon $startDate, Carbon $endDate, string $selectedKasir): Builder
    {
        $query = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereNotIn('order_status', [
                Order::STATUS_CANCELLED,
                Order::STATUS_REJECTED,
                Order::STATUS_EXPIRED,
            ]);

        if ($selectedKasir !== 'all') {
            $query->where(function ($query) use ($selectedKasir) {
                $query
                    ->where('cashier_id', $selectedKasir)
                    ->orWhereHas('shift', function ($shiftQuery) use ($selectedKasir) {
                        $shiftQuery->where('user_id', $selectedKasir);
                    });
            });
        }

        return $query;
    }

    private function expenseTotal(Carbon $startDate, Carbon $endDate, string $selectedKasir): int
    {
        $query = ShiftExpense::query()
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($selectedKasir !== 'all') {
            $query->where('user_id', $selectedKasir);
        }

        return (int) $query->sum('amount');
    }

    private function summaryCards(
        int $totalRevenue,
        int $totalOrder,
        int $averageOrder,
        int $netRevenue
    ): array {
        return [
            [
                'title' => 'Total Pendapatan',
                'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                'accent' => 'from-emerald-500 to-emerald-300',
            ],
            [
                'title' => 'Order Paid',
                'value' => number_format($totalOrder, 0, ',', '.'),
                'accent' => 'from-blue-500 to-blue-300',
            ],
            [
                'title' => 'Rata-rata Order',
                'value' => 'Rp ' . number_format($averageOrder, 0, ',', '.'),
                'accent' => 'from-amber-500 to-amber-300',
            ],
            [
                'title' => 'Sisa Operasional',
                'value' => 'Rp ' . number_format($netRevenue, 0, ',', '.'),
                'accent' => 'from-stone-700 to-stone-400',
            ],
        ];
    }

    private function chartData(array $chartRows): Collection
    {
        $maxAmount = max((int) (collect($chartRows)->max('amount') ?? 0), 1);

        return collect($chartRows)
            ->map(function ($item) use ($maxAmount) {
                $amount = (int) ($item['amount'] ?? 0);

                return [
                    'label' => $item['label'],
                    'amount' => $amount,
                    'value' => $amount > 0
                        ? max(8, (int) round(($amount / $maxAmount) * 100))
                        : 3,
                ];
            })
            ->values();
    }

    private function busiestSubtitle(string $periode): string
    {
        return match ($periode) {
            'harian' => 'Hari Paling Ramai',
            'bulanan' => 'Bulan Paling Ramai',
            'tahunan' => 'Tahun Paling Ramai',
            default => 'Periode Teramai',
        };
    }
}