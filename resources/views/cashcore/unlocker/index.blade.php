@extends('cashcore.layout')

@section('cashcore_content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">{{ __('cashcore.cash_unlocker') }}</h2>
        <p class="text-gray-400 text-sm mt-1">{{ __('cashcore.unlocker_subtitle') }}</p>
    </div>
    <a href="{{ route('cashcore.unlocker.create') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-medium rounded-xl transition-all">+ {{ __('cashcore.add_blocker') }}</a>
</div>

{{-- Unlockable Capital --}}
<div class="bg-gradient-to-r from-purple-900/30 to-purple-800/10 border border-purple-500/20 rounded-2xl p-8 text-center mb-8">
    <p class="text-sm text-purple-300 mb-2">{{ __('cashcore.unlockable_capital') }}</p>
    <p class="text-5xl font-bold text-purple-400">&euro; {{ number_format($totalBlocked, 2) }}</p>
    <p class="text-sm text-purple-300/70 mt-2">{{ __('cashcore.unlockable_desc', ['amount' => number_format($totalBlocked, 2) . ' €']) }}</p>
</div>

{{-- Active Blockers --}}
<h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.active_blockers') }} ({{ $activeBlockers->count() }})</h3>
@if($activeBlockers->isNotEmpty())
    <div class="space-y-3 mb-8">
        @foreach($activeBlockers as $b)
            <div class="bg-gray-900/50 border border-purple-500/20 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-purple-500/20 text-purple-400">{{ __('cashcore.' . $b->blocker_type) }}</span>
                            @if($b->days_overdue > 0)<span class="text-xs text-red-400">{{ $b->days_overdue }} {{ __('cashcore.days_overdue') }}</span>@endif
                        </div>
                        <p class="text-white font-medium">{{ $b->title }}</p>
                        @if($b->debtor_name)<p class="text-gray-400 text-sm">{{ __('cashcore.debtor_name') }}: {{ $b->debtor_name }}</p>@endif
                        @if($b->description)<p class="text-gray-400 text-sm mt-1">{{ $b->description }}</p>@endif
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-purple-400 font-bold text-xl">&euro; {{ number_format($b->blocked_amount, 2) }}</p>
                        @if($b->due_date)<p class="text-gray-500 text-xs">{{ __('cashcore.due_date') }}: {{ $b->due_date->format('d.m.Y') }}</p>@endif
                        <div class="flex gap-2 mt-2 justify-end">
                            <a href="{{ route('cashcore.unlocker.edit', $b) }}" class="px-3 py-1.5 border border-white/10 text-gray-400 hover:text-white text-xs font-medium rounded-lg">{{ __('cashcore.edit') }}</a>
                            <form method="POST" action="{{ route('cashcore.unlocker.status', $b) }}">@csrf @method('PUT')
                                <input type="hidden" name="status" value="resolved">
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium rounded-lg">{{ __('cashcore.mark_resolved') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-8 text-center text-gray-500 mb-8">{{ __('cashcore.no_data') }}</div>
@endif

@if($resolvedBlockers->isNotEmpty())
    <h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.resolved_blockers') }}</h3>
    <div class="space-y-2">
        @foreach($resolvedBlockers as $b)
            <div class="bg-gray-900/30 border border-white/5 rounded-xl p-4 flex items-center justify-between opacity-60">
                <span class="text-white text-sm">{{ $b->title }}</span>
                <span class="text-gray-500 text-sm">&euro; {{ number_format($b->blocked_amount, 2) }}</span>
            </div>
        @endforeach
    </div>
@endif
@endsection
