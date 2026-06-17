@extends('cashcore.layout')

@section('cashcore_content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-white">{{ __('cashcore.expense_scoring') }}</h2>
    <p class="text-gray-400 text-sm mt-1">{{ __('cashcore.scoring_subtitle') }}</p>
</div>

{{-- Unscored Expenses --}}
@if($unscoredExpenses->isNotEmpty())
    <h3 class="text-lg font-semibold text-amber-400 mb-4">{{ __('cashcore.unscored_expenses') }} ({{ $unscoredExpenses->count() }})</h3>
    <div class="grid gap-3 mb-8">
        @foreach($unscoredExpenses as $tx)
            <div class="bg-gray-900/50 border border-amber-500/20 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-white font-medium">{{ $tx->description }}</p>
                    <p class="text-gray-400 text-sm">{{ $tx->category?->icon }} {{ $tx->category?->name ?? '-' }} &middot; {{ $tx->transaction_date->format('d.m.Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <p class="text-red-400 font-bold">&euro; {{ number_format($tx->amount, 2) }}</p>
                    <a href="{{ route('cashcore.scoring.score', $tx) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-medium rounded-xl transition-all">{{ __('cashcore.score_expense') }}</a>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Scored Expenses --}}
<h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.scored_expenses') }} ({{ $scoredExpenses->count() }})</h3>
@if($scoredExpenses->isNotEmpty())
    <div class="grid gap-3">
        @foreach($scoredExpenses as $tx)
            @php $s = $tx->expenseScore; @endphp
            <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-5 flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <p class="text-white font-medium">{{ $tx->description }}</p>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $s->recommendation === 'keep' ? 'bg-emerald-500/20 text-emerald-400' : ($s->recommendation === 'reduce' ? 'bg-amber-500/20 text-amber-400' : 'bg-red-500/20 text-red-400') }}">{{ __('cashcore.' . $s->recommendation) }}</span>
                    </div>
                    <p class="text-gray-400 text-sm">{{ $s->purpose }} &middot; {{ $s->benefit }}</p>
                    <div class="flex gap-4 mt-2">
                        <span class="text-xs text-gray-500">{{ __('cashcore.revenue_contribution') }}: {{ $s->revenue_score }}/10</span>
                        <span class="text-xs text-gray-500">{{ __('cashcore.efficiency_contribution') }}: {{ $s->efficiency_score }}/10</span>
                        <span class="text-xs text-gray-500">{{ __('cashcore.strategic_value') }}: {{ $s->strategic_score }}/10</span>
                        <span class="text-xs text-gray-500">{{ __('cashcore.usage_level') }}: {{ $s->usage_score }}/10</span>
                    </div>
                </div>
                <div class="text-right ml-4">
                    <p class="text-red-400 font-bold">&euro; {{ number_format($tx->amount, 2) }}</p>
                    <p class="text-lg font-bold {{ $s->total_score >= 28 ? 'text-emerald-400' : ($s->total_score >= 15 ? 'text-amber-400' : 'text-red-400') }}">{{ $s->total_score }}/40</p>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-8 text-center text-gray-500">{{ __('cashcore.no_data') }}</div>
@endif
@endsection
