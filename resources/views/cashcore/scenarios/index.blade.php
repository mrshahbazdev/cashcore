@extends('cashcore.layout')

@section('cashcore_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">{{ __('cashcore.scenarios') }}</h2>
        <p class="text-gray-400 text-sm mt-1">{{ __('cashcore.scenario_subtitle') }}</p>
    </div>
    <a href="{{ route('cashcore.scenarios.create') }}" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-medium rounded-xl transition-all">+ {{ __('cashcore.create_scenario') }}</a>
</div>

@if($scenarios->isNotEmpty())
    <div class="grid gap-4">
        @foreach($scenarios as $s)
            @php
                $currentProfit = $s->current_revenue - $s->current_costs;
                $projectedProfit = $s->adjusted_revenue - $s->adjusted_costs;
                $delta = $projectedProfit - $currentProfit;
            @endphp
            <div class="bg-gray-900/50 border border-cyan-500/20 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-white">{{ $s->name }}</h3>
                        @if($s->description)<p class="text-gray-400 text-sm">{{ $s->description }}</p>@endif
                    </div>
                    <form method="POST" action="{{ route('cashcore.scenarios.destroy', $s) }}" onsubmit="return confirm('{{ __('cashcore.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm">{{ __('cashcore.delete') }}</button>
                    </form>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-800/30 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 mb-1">{{ __('cashcore.before') }}</p>
                        <p class="text-sm text-gray-400">{{ __('cashcore.income') }}: &euro; {{ number_format($s->current_revenue, 2) }}</p>
                        <p class="text-sm text-gray-400">{{ __('cashcore.expense') }}: &euro; {{ number_format($s->current_costs, 2) }}</p>
                        <p class="text-lg font-bold {{ $currentProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }} mt-1">&euro; {{ number_format($currentProfit, 2) }}</p>
                    </div>
                    <div class="bg-gray-800/30 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500 mb-1">{{ __('cashcore.after') }}</p>
                        <p class="text-sm text-gray-400">{{ __('cashcore.income') }}: &euro; {{ number_format($s->adjusted_revenue, 2) }}</p>
                        <p class="text-sm text-gray-400">{{ __('cashcore.expense') }}: &euro; {{ number_format($s->adjusted_costs, 2) }}</p>
                        <p class="text-lg font-bold {{ $projectedProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }} mt-1">&euro; {{ number_format($projectedProfit, 2) }}</p>
                    </div>
                    <div class="bg-gray-800/30 rounded-xl p-4 text-center flex flex-col items-center justify-center">
                        <p class="text-xs text-gray-500 mb-1">{{ __('cashcore.difference') }}</p>
                        <p class="text-3xl font-bold {{ $delta >= 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ $delta >= 0 ? '+' : '' }}&euro; {{ number_format($delta, 2) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-8 text-center text-gray-500">
        <p class="mb-4">{{ __('cashcore.no_data') }}</p>
        <div class="text-sm space-y-1">
            @foreach(__('cashcore.scenario_examples') as $ex)
                <p class="text-cyan-400/60">"{{ $ex }}"</p>
            @endforeach
        </div>
    </div>
@endif
@endsection
