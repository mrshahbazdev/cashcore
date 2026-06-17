@extends('cashcore.layout')

@section('cashcore_content')
{{-- Period Selector --}}
<div class="flex items-center gap-4 mb-8">
    <form method="GET" class="flex items-center gap-2">
        <input type="month" name="period" value="{{ $period }}" class="px-4 py-2 bg-gray-800/50 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-xl transition-all">{{ __('cashcore.filter_all') }}</button>
    </form>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <p class="text-sm text-gray-400 mb-1">{{ __('cashcore.total_income') }}</p>
        <p class="text-2xl font-bold text-emerald-400">&euro; {{ number_format($income, 2) }}</p>
        @if($incomeChange != 0)
            <p class="text-xs mt-1 {{ $incomeChange > 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ $incomeChange > 0 ? '+' : '' }}{{ $incomeChange }}% {{ __('cashcore.vs_last_month') }}</p>
        @endif
    </div>
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <p class="text-sm text-gray-400 mb-1">{{ __('cashcore.total_expenses') }}</p>
        <p class="text-2xl font-bold text-red-400">&euro; {{ number_format($expenses, 2) }}</p>
        @if($expenseChange != 0)
            <p class="text-xs mt-1 {{ $expenseChange > 0 ? 'text-red-400' : 'text-emerald-400' }}">{{ $expenseChange > 0 ? '+' : '' }}{{ $expenseChange }}% {{ __('cashcore.vs_last_month') }}</p>
        @endif
    </div>
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <p class="text-sm text-gray-400 mb-1">{{ __('cashcore.net_profit') }}</p>
        <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }}">&euro; {{ number_format($netProfit, 2) }}</p>
        <p class="text-xs mt-1 text-gray-500">{{ __('cashcore.profit_margin') }}: {{ $profitMargin }}%</p>
    </div>
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <p class="text-sm text-gray-400 mb-1">{{ __('cashcore.leak_score') }}</p>
        <div class="flex items-center gap-3">
            <p class="text-2xl font-bold {{ $leakScore < 30 ? 'text-emerald-400' : ($leakScore < 60 ? 'text-amber-400' : 'text-red-400') }}">{{ $leakScore }}</p>
            <div class="flex-1 bg-gray-800 rounded-full h-2">
                <div class="h-2 rounded-full {{ $leakScore < 30 ? 'bg-emerald-500' : ($leakScore < 60 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $leakScore }}%"></div>
            </div>
        </div>
        <p class="text-xs mt-1 text-gray-500">{{ $activeLeaks }} {{ __('cashcore.active_leaks') }}</p>
    </div>
</div>

{{-- Cost / Profit / Overhead Percentages --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-5 text-center">
        <p class="text-4xl font-bold text-red-400">{{ $costRatio }}%</p>
        <p class="text-sm text-gray-400 mt-1">{{ __('cashcore.cost_pct') }}</p>
    </div>
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-5 text-center">
        <p class="text-4xl font-bold text-emerald-400">{{ $profitMargin }}%</p>
        <p class="text-sm text-gray-400 mt-1">{{ __('cashcore.profit_pct') }}</p>
    </div>
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-5 text-center">
        <p class="text-4xl font-bold text-amber-400">{{ $overheadRatio }}%</p>
        <p class="text-sm text-gray-400 mt-1">{{ __('cashcore.overhead_pct') }}</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-8">
    {{-- Monthly Trend Chart --}}
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.monthly_trend') }}</h3>
        <canvas id="trendChart" height="200"></canvas>
    </div>

    {{-- Expense Breakdown --}}
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.expense_breakdown') }}</h3>
        @if($categoryBreakdown->isNotEmpty())
            <canvas id="breakdownChart" height="200"></canvas>
        @else
            <p class="text-gray-500 text-sm">{{ __('cashcore.no_data') }}</p>
        @endif
    </div>
</div>

{{-- Recent Transactions --}}
<div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-white">{{ __('cashcore.recent_transactions') }}</h3>
        <a href="{{ route('cashcore.transactions.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-xl transition-all">+ {{ __('cashcore.add_transaction') }}</a>
    </div>
    @if($recentTransactions->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-gray-400 border-b border-white/5">
                    <th class="text-left py-3 px-2">{{ __('cashcore.date') }}</th>
                    <th class="text-left py-3 px-2">{{ __('cashcore.description') }}</th>
                    <th class="text-left py-3 px-2">{{ __('cashcore.category') }}</th>
                    <th class="text-right py-3 px-2">{{ __('cashcore.amount') }}</th>
                </tr></thead>
                <tbody>
                    @foreach($recentTransactions as $tx)
                        <tr class="border-b border-white/5 hover:bg-white/5">
                            <td class="py-3 px-2 text-gray-400">{{ $tx->transaction_date->format('d.m.Y') }}</td>
                            <td class="py-3 px-2 text-white">{{ $tx->description }}</td>
                            <td class="py-3 px-2 text-gray-400">{{ $tx->category?->name ?? '-' }}</td>
                            <td class="py-3 px-2 text-right font-medium {{ $tx->type === 'income' ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $tx->type === 'income' ? '+' : '-' }}&euro; {{ number_format($tx->amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 text-sm">{{ __('cashcore.no_data') }}</p>
    @endif
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    const trendData = @json($monthlyTrend);
    new Chart(document.getElementById('trendChart'), {
        type: 'bar',
        data: {
            labels: trendData.map(d => d.month_short),
            datasets: [
                { label: '{{ __("cashcore.income") }}', data: trendData.map(d => d.income), backgroundColor: 'rgba(16,185,129,0.6)', borderRadius: 6 },
                { label: '{{ __("cashcore.expense") }}', data: trendData.map(d => d.expenses), backgroundColor: 'rgba(239,68,68,0.6)', borderRadius: 6 },
            ]
        },
        options: { responsive: true, plugins: { legend: { labels: { color: '#9ca3af' } } }, scales: { x: { ticks: { color: '#6b7280' }, grid: { display: false } }, y: { ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.05)' } } } }
    });

    @if($categoryBreakdown->isNotEmpty())
    new Chart(document.getElementById('breakdownChart'), {
        type: 'doughnut',
        data: {
            labels: @json($categoryBreakdown->pluck('name')),
            datasets: [{ data: @json($categoryBreakdown->pluck('total')), backgroundColor: @json($categoryBreakdown->pluck('color')), borderWidth: 0 }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af', padding: 12 } } } }
    });
    @endif
</script>
@endsection
