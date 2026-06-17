@extends('cashcore.layout')

@section('cashcore_content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-white">{{ __('cashcore.profit_allocation') }}</h2>
    <p class="text-gray-400 text-sm mt-1">{{ __('cashcore.allocation_subtitle') }}</p>
</div>

{{-- Period Selector --}}
<div class="flex items-center gap-4 mb-8">
    <form method="GET" class="flex items-center gap-2">
        <input type="month" name="period" value="{{ $period }}" class="px-4 py-2 bg-gray-800/50 border border-white/10 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
        <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white text-sm font-medium rounded-xl transition-all">{{ __('cashcore.filter_all') }}</button>
    </form>
</div>

{{-- Revenue for Period --}}
<div class="bg-gradient-to-r from-teal-900/30 to-teal-800/10 border border-teal-500/20 rounded-2xl p-6 text-center mb-8">
    <p class="text-sm text-teal-300 mb-1">{{ __('cashcore.total_revenue_for_period') }}</p>
    <p class="text-4xl font-bold text-teal-400">&euro; {{ number_format($totalRevenue, 2) }}</p>
</div>

{{-- Allocation Buckets --}}
<div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6 mb-8">
    <form method="POST" action="{{ route('cashcore.allocation.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="period" value="{{ $period }}">

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $bucketColors = ['profit' => 'emerald', 'taxes' => 'red', 'salary' => 'blue', 'operations' => 'amber'];
                $bucketIcons = ['profit' => '$', 'taxes' => '%', 'salary' => 'S', 'operations' => 'O'];
            @endphp
            @foreach($allocations as $i => $alloc)
                @php $color = $bucketColors[$alloc->bucket] ?? 'gray'; @endphp
                <div class="bg-gray-800/30 border border-{{ $color }}-500/20 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-8 h-8 bg-{{ $color }}-500/20 rounded-lg flex items-center justify-center text-{{ $color }}-400 text-sm font-bold">{{ $bucketIcons[$alloc->bucket] ?? '?' }}</span>
                        <span class="text-white font-medium">{{ __('cashcore.bucket_' . $alloc->bucket) }}</span>
                    </div>
                    <input type="hidden" name="allocations[{{ $i }}][bucket]" value="{{ $alloc->bucket }}">
                    <div class="mb-3">
                        <label class="text-xs text-gray-500">{{ __('cashcore.allocation_percentage') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="number" step="0.01" min="0" max="100" name="allocations[{{ $i }}][percentage]" value="{{ $alloc->percentage }}" class="w-full px-3 py-2 bg-gray-800/50 border border-white/10 rounded-lg text-white text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                            <span class="text-gray-400">%</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('cashcore.allocated_amount') }}</label>
                        <p class="text-xl font-bold text-{{ $color }}-400">&euro; {{ number_format($alloc->allocated_amount, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ __('cashcore.allocations_must_equal_100') }}</p>
            <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-xl transition-all">{{ __('cashcore.save') }}</button>
        </div>
    </form>
</div>

{{-- Allocation Visual --}}
@if($allocations->sum('allocated_amount') > 0)
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.profit_allocation') }}</h3>
        <canvas id="allocationChart" height="150"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        new Chart(document.getElementById('allocationChart'), {
            type: 'doughnut',
            data: {
                labels: @json($allocations->map(fn($a) => __('cashcore.bucket_' . $a->bucket))),
                datasets: [{
                    data: @json($allocations->pluck('allocated_amount')),
                    backgroundColor: ['#10b981', '#ef4444', '#3b82f6', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af', padding: 16 } } } }
        });
    </script>
@endif
@endsection
