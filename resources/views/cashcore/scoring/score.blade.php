@extends('cashcore.layout')

@section('cashcore_content')
<h2 class="text-2xl font-bold text-white mb-6">{{ __('cashcore.score_expense') }}</h2>

<div class="max-w-2xl">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6 mb-6">
        <p class="text-white font-medium text-lg">{{ $transaction->description }}</p>
        <p class="text-red-400 font-bold text-2xl mt-1">&euro; {{ number_format($transaction->amount, 2) }}</p>
        <p class="text-gray-400 text-sm mt-1">{{ $transaction->category?->name ?? '-' }} &middot; {{ $transaction->transaction_date->format('d.m.Y') }}</p>
    </div>

    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <form method="POST" action="{{ route('cashcore.scoring.store', $transaction) }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.purpose_question') }}</label>
                <input type="text" name="purpose" value="{{ old('purpose') }}" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500" placeholder="{{ __('cashcore.purpose') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.benefit_question') }}</label>
                <input type="text" name="benefit" value="{{ old('benefit') }}" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500" placeholder="{{ __('cashcore.benefit') }}">
            </div>

            @foreach(['revenue_score' => __('cashcore.revenue_contribution'), 'efficiency_score' => __('cashcore.efficiency_contribution'), 'strategic_score' => __('cashcore.strategic_value'), 'usage_score' => __('cashcore.usage_level')] as $field => $label)
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ $label }} <span class="text-gray-500">(0-10)</span></label>
                    <input type="range" name="{{ $field }}" min="0" max="10" value="{{ old($field, 5) }}" class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-emerald-500" oninput="this.nextElementSibling.textContent = this.value">
                    <span class="text-emerald-400 font-bold text-lg">{{ old($field, 5) }}</span>
                </div>
            @endforeach

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-all">{{ __('cashcore.save') }}</button>
                <a href="{{ route('cashcore.scoring.index') }}" class="px-6 py-3 border border-white/10 text-gray-300 hover:text-white rounded-xl transition-all">{{ __('cashcore.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
