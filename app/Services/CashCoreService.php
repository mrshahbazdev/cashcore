<?php

namespace App\Services;

use App\Models\CashAlert;
use App\Models\CashCategory;
use App\Models\CashLeak;
use App\Models\CashTransaction;
use App\Models\ExpenseScore;
use App\Models\LiquidityBlocker;
use App\Models\ProfitAllocation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CashCoreService
{
    public function getDashboardData(int $userId, string $period = null): array
    {
        $period = $period ?? now()->format('Y-m');
        $start = Carbon::parse($period . '-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $prevStart = $start->copy()->subMonth()->startOfMonth();
        $prevEnd = $prevStart->copy()->endOfMonth();

        $income = CashTransaction::where('user_id', $userId)
            ->income()
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $expenses = CashTransaction::where('user_id', $userId)
            ->expense()
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $prevIncome = CashTransaction::where('user_id', $userId)
            ->income()
            ->whereBetween('transaction_date', [$prevStart, $prevEnd])
            ->sum('amount');

        $prevExpenses = CashTransaction::where('user_id', $userId)
            ->expense()
            ->whereBetween('transaction_date', [$prevStart, $prevEnd])
            ->sum('amount');

        $netProfit = $income - $expenses;
        $profitMargin = $income > 0 ? round(($netProfit / $income) * 100, 1) : 0;
        $costRatio = $income > 0 ? round(($expenses / $income) * 100, 1) : 0;

        $overheadCategories = ['Rent / Office', 'Insurance', 'Miscellaneous'];
        $overhead = CashTransaction::where('user_id', $userId)
            ->expense()
            ->whereBetween('transaction_date', [$start, $end])
            ->whereHas('category', fn ($q) => $q->whereIn('name', $overheadCategories))
            ->sum('amount');
        $overheadRatio = $income > 0 ? round(($overhead / $income) * 100, 1) : 0;

        $incomeChange = $prevIncome > 0 ? round((($income - $prevIncome) / $prevIncome) * 100, 1) : 0;
        $expenseChange = $prevExpenses > 0 ? round((($expenses - $prevExpenses) / $prevExpenses) * 100, 1) : 0;

        $categoryBreakdown = CashTransaction::where('cash_transactions.user_id', $userId)
            ->expense()
            ->whereBetween('cash_transactions.transaction_date', [$start, $end])
            ->join('cash_categories', 'cash_transactions.cash_category_id', '=', 'cash_categories.id')
            ->select('cash_categories.name', 'cash_categories.color', 'cash_categories.icon')
            ->selectRaw('SUM(cash_transactions.amount) as total')
            ->groupBy('cash_categories.name', 'cash_categories.color', 'cash_categories.icon')
            ->orderByDesc('total')
            ->get();

        $recentTransactions = CashTransaction::where('user_id', $userId)
            ->with('category')
            ->orderByDesc('transaction_date')
            ->limit(10)
            ->get();

        $monthlyTrend = $this->getMonthlyTrend($userId, 6);

        $leakScore = $this->calculateOverallLeakScore($userId);
        $activeLeaks = CashLeak::where('user_id', $userId)->active()->count();
        $unreadAlerts = CashAlert::where('user_id', $userId)->unread()->count();

        return compact(
            'income', 'expenses', 'netProfit', 'profitMargin',
            'costRatio', 'overheadRatio', 'incomeChange', 'expenseChange',
            'categoryBreakdown', 'recentTransactions', 'monthlyTrend',
            'leakScore', 'activeLeaks', 'unreadAlerts', 'period'
        );
    }

    public function getMonthlyTrend(int $userId, int $months = 6): Collection
    {
        $results = collect();
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $income = CashTransaction::where('user_id', $userId)
                ->income()
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('amount');

            $expenses = CashTransaction::where('user_id', $userId)
                ->expense()
                ->whereBetween('transaction_date', [$start, $end])
                ->sum('amount');

            $results->push([
                'month' => $date->format('M Y'),
                'month_short' => $date->format('M'),
                'income' => (float) $income,
                'expenses' => (float) $expenses,
                'profit' => (float) ($income - $expenses),
            ]);
        }

        return $results;
    }

    public function runLeakDetection(int $userId): array
    {
        $leaks = [];
        $period = now()->format('Y-m');
        $prevPeriod = now()->subMonth()->format('Y-m');

        $currentExpenses = CashTransaction::where('user_id', $userId)
            ->expense()->forPeriod($period)->sum('amount');
        $prevExpenses = CashTransaction::where('user_id', $userId)
            ->expense()->forPeriod($prevPeriod)->sum('amount');
        $currentIncome = CashTransaction::where('user_id', $userId)
            ->income()->forPeriod($period)->sum('amount');
        $prevIncome = CashTransaction::where('user_id', $userId)
            ->income()->forPeriod($prevPeriod)->sum('amount');

        if ($prevExpenses > 0 && $prevIncome > 0) {
            $expGrowth = ($currentExpenses - $prevExpenses) / $prevExpenses;
            $incGrowth = $prevIncome > 0 ? ($currentIncome - $prevIncome) / $prevIncome : 0;

            if ($expGrowth > 0.1 && $expGrowth > $incGrowth) {
                $leaks[] = [
                    'leak_type' => 'rising_costs',
                    'title' => __('cashcore.rising_costs'),
                    'description' => __('cashcore.alert_cost_rising'),
                    'monthly_amount' => $currentExpenses - $prevExpenses,
                    'leak_score' => min(100, (int) ($expGrowth * 100)),
                ];
            }
        }

        $recurring = CashTransaction::where('user_id', $userId)
            ->expense()->recurring()
            ->with('category', 'expenseScore')
            ->get();

        foreach ($recurring as $tx) {
            if ($tx->expenseScore && $tx->expenseScore->usage_score <= 3) {
                $leaks[] = [
                    'cash_transaction_id' => $tx->id,
                    'leak_type' => 'unused_subscription',
                    'title' => __('cashcore.unused_subscription') . ': ' . $tx->description,
                    'description' => __('cashcore.leak_example', ['amount' => number_format($tx->amount, 2) . ' €']),
                    'monthly_amount' => $tx->amount,
                    'leak_score' => max(30, 100 - ($tx->expenseScore->usage_score * 10)),
                ];
            }
        }

        $unscoredRecurring = CashTransaction::where('user_id', $userId)
            ->expense()->recurring()
            ->doesntHave('expenseScore')
            ->get();

        foreach ($unscoredRecurring as $tx) {
            $leaks[] = [
                'cash_transaction_id' => $tx->id,
                'leak_type' => 'no_function',
                'title' => __('cashcore.no_function') . ': ' . $tx->description,
                'description' => __('cashcore.leak_example', ['amount' => number_format($tx->amount, 2) . ' €']),
                'monthly_amount' => $tx->amount,
                'leak_score' => 50,
            ];
        }

        foreach ($leaks as $leak) {
            CashLeak::updateOrCreate(
                [
                    'user_id' => $userId,
                    'leak_type' => $leak['leak_type'],
                    'title' => $leak['title'],
                    'status' => 'detected',
                ],
                array_merge($leak, ['user_id' => $userId])
            );
        }

        return $leaks;
    }

    public function calculateOverallLeakScore(int $userId): int
    {
        $activeLeaks = CashLeak::where('user_id', $userId)->active()->get();

        if ($activeLeaks->isEmpty()) {
            return 0;
        }

        return (int) min(100, $activeLeaks->avg('leak_score'));
    }

    public function calculateProfitAllocations(int $userId, string $period): void
    {
        $start = Carbon::parse($period . '-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $totalRevenue = CashTransaction::where('user_id', $userId)
            ->income()
            ->whereBetween('transaction_date', [$start, $end])
            ->sum('amount');

        $allocations = ProfitAllocation::where('user_id', $userId)
            ->where('period', $period)
            ->get();

        if ($allocations->isEmpty()) {
            ProfitAllocation::initializeForUser($userId, $period);
            $allocations = ProfitAllocation::where('user_id', $userId)
                ->where('period', $period)
                ->get();
        }

        foreach ($allocations as $allocation) {
            $allocation->allocated_amount = ($totalRevenue * $allocation->percentage) / 100;
            $allocation->save();
        }
    }

    public function generateAlerts(int $userId): void
    {
        $period = now()->format('Y-m');
        $prevPeriod = now()->subMonth()->format('Y-m');

        $currentExpenses = CashTransaction::where('user_id', $userId)
            ->expense()->forPeriod($period)->sum('amount');
        $currentIncome = CashTransaction::where('user_id', $userId)
            ->income()->forPeriod($period)->sum('amount');
        $prevExpenses = CashTransaction::where('user_id', $userId)
            ->expense()->forPeriod($prevPeriod)->sum('amount');
        $prevIncome = CashTransaction::where('user_id', $userId)
            ->income()->forPeriod($prevPeriod)->sum('amount');

        if ($prevExpenses > 0 && $prevIncome > 0) {
            $expGrowth = ($currentExpenses - $prevExpenses) / $prevExpenses;
            $incGrowth = ($currentIncome - $prevIncome) / $prevIncome;

            if ($expGrowth > $incGrowth && $expGrowth > 0.05) {
                CashAlert::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'alert_type' => 'cost_rising',
                        'is_dismissed' => false,
                        'is_read' => false,
                    ],
                    [
                        'severity' => 'warning',
                        'title' => __('cashcore.alert_cost_rising'),
                        'message' => __('cashcore.alert_cost_rising'),
                    ]
                );
            }
        }

        if ($currentIncome > 0 && ($currentIncome - $currentExpenses) / $currentIncome < 0.05) {
            CashAlert::firstOrCreate(
                [
                    'user_id' => $userId,
                    'alert_type' => 'profit_low',
                    'is_dismissed' => false,
                    'is_read' => false,
                ],
                [
                    'severity' => 'critical',
                    'title' => __('cashcore.alert_profit_low'),
                    'message' => __('cashcore.alert_profit_low'),
                ]
            );
        }
    }

    public function importCsv(int $userId, string $filePath): int
    {
        $count = 0;
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return 0;
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return 0;
        }

        $header = array_map('strtolower', array_map('trim', $header));

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) {
                continue;
            }

            $data = array_combine($header, $row);

            $dateStr = $data['date'] ?? $data['datum'] ?? null;
            $description = $data['description'] ?? $data['beschreibung'] ?? '';
            $amount = abs((float) str_replace(',', '.', $data['amount'] ?? $data['betrag'] ?? '0'));
            $type = strtolower($data['type'] ?? $data['typ'] ?? 'expense');

            if (!$dateStr || $amount <= 0) {
                continue;
            }

            $type = in_array($type, ['income', 'einnahme']) ? 'income' : 'expense';

            CashTransaction::create([
                'user_id' => $userId,
                'type' => $type,
                'amount' => $amount,
                'description' => $description,
                'transaction_date' => Carbon::parse($dateStr)->format('Y-m-d'),
                'source' => 'csv_import',
            ]);

            $count++;
        }

        fclose($handle);
        return $count;
    }
}
