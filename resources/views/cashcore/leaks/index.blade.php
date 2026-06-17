@extends('cashcore.layout')

@section('cashcore_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">{{ __('cashcore.leak_detection') }}</h2>
        <p class="text-gray-400 text-sm mt-1">{{ __('cashcore.leak_subtitle') }}</p>
    </div>
    <form method="POST" action="{{ route('cashcore.leaks.detect') }}">
        @csrf
        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-medium rounded-xl transition-all">{{ __('cashcore.run_detection') }}</button>
    </form>
</div>

{{-- Overall Score --}}
<div class="grid sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6 text-center">
        <p class="text-sm text-gray-400 mb-2">{{ __('cashcore.overall_leak_score') }}</p>
        <p class="text-5xl font-bold {{ $overallScore < 30 ? 'text-emerald-400' : ($overallScore < 60 ? 'text-amber-400' : 'text-red-400') }}">{{ $overallScore }}</p>
        <div class="w-full bg-gray-800 rounded-full h-2 mt-3"><div class="h-2 rounded-full {{ $overallScore < 30 ? 'bg-emerald-500' : ($overallScore < 60 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $overallScore }}%"></div></div>
        <p class="text-xs text-gray-500 mt-2">{{ __('cashcore.leak_score_desc') }}</p>
    </div>
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6 text-center">
        <p class="text-sm text-gray-400 mb-2">{{ __('cashcore.active_leaks') }}</p>
        <p class="text-5xl font-bold text-red-400">{{ $activeLeaks->count() }}</p>
    </div>
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6 text-center">
        <p class="text-sm text-gray-400 mb-2">{{ __('cashcore.total_leak_amount') }}</p>
        <p class="text-3xl font-bold text-red-400">&euro; {{ number_format($totalLeakAmount, 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">/ {{ __('cashcore.monthly') }}</p>
    </div>
</div>

{{-- Active Leaks --}}
<h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.active_leaks') }}</h3>
@if($activeLeaks->isNotEmpty())
    <div class="space-y-3 mb-8">
        @foreach($activeLeaks as $leak)
            <div class="bg-gray-900/50 border border-red-500/20 rounded-2xl p-5 flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-500/20 text-red-400">{{ __('cashcore.' . $leak->leak_type) }}</span>
                        <span class="text-xs text-gray-500">Score: {{ $leak->leak_score }}</span>
                    </div>
                    <p class="text-white font-medium">{{ $leak->title }}</p>
                    @if($leak->description)<p class="text-gray-400 text-sm mt-1">{{ $leak->description }}</p>@endif
                </div>
                <div class="flex items-center gap-4 ml-4">
                    <p class="text-red-400 font-bold text-lg">&euro; {{ number_format($leak->monthly_amount, 2) }}/m</p>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('cashcore.leaks.status', $leak) }}">@csrf @method('PUT')
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium rounded-lg transition-all">{{ __('cashcore.resolve') }}</button>
                        </form>
                        <form method="POST" action="{{ route('cashcore.leaks.status', $leak) }}">@csrf @method('PUT')
                            <input type="hidden" name="status" value="ignored">
                            <button type="submit" class="px-3 py-1.5 border border-white/10 text-gray-400 hover:text-white text-xs font-medium rounded-lg transition-all">{{ __('cashcore.ignore') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-gray-900/50 border border-emerald-500/20 rounded-2xl p-8 text-center mb-8">
        <p class="text-emerald-400 font-medium">{{ __('cashcore.no_leaks_found') }}</p>
    </div>
@endif

{{-- Resolved Leaks --}}
@if($resolvedLeaks->isNotEmpty())
    <h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.resolved_leaks') }}</h3>
    <div class="space-y-2">
        @foreach($resolvedLeaks as $leak)
            <div class="bg-gray-900/30 border border-white/5 rounded-xl p-4 flex items-center justify-between opacity-60">
                <div>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500/20 text-emerald-400">{{ __('cashcore.resolve') }}</span>
                    <span class="text-white text-sm ml-2">{{ $leak->title }}</span>
                </div>
                <span class="text-gray-500 text-sm">&euro; {{ number_format($leak->monthly_amount, 2) }}/m</span>
            </div>
        @endforeach
    </div>
@endif
@endsection
