@extends('cashcore.layout')

@section('cashcore_content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-white">{{ __('cashcore.behavior_system') }}</h2>
    <p class="text-gray-400 text-sm mt-1">{{ __('cashcore.behavior_subtitle') }}</p>
</div>

<div class="grid sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-gray-900/50 border border-blue-500/20 rounded-2xl p-6 text-center">
        <p class="text-sm text-gray-400 mb-2">{{ __('cashcore.current_streak') }}</p>
        <p class="text-4xl font-bold text-blue-400">{{ $streak }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ __('cashcore.streak_months', ['count' => $streak]) }}</p>
    </div>
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6 text-center">
        <p class="text-sm text-gray-400 mb-2">{{ __('cashcore.pending_reviews') }}</p>
        <p class="text-4xl font-bold text-amber-400">{{ $pendingReviews->count() }}</p>
    </div>
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6 text-center">
        <p class="text-sm text-gray-400 mb-2">{{ __('cashcore.unread_alerts') }}</p>
        <p class="text-4xl font-bold text-red-400">{{ $alerts->count() }}</p>
    </div>
</div>

{{-- Alerts --}}
@if($alerts->isNotEmpty())
    <h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.alerts') }}</h3>
    <div class="space-y-3 mb-8">
        @foreach($alerts as $alert)
            <div class="bg-gray-900/50 border {{ $alert->severity === 'critical' ? 'border-red-500/30' : ($alert->severity === 'warning' ? 'border-amber-500/30' : 'border-blue-500/30') }} rounded-2xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $alert->severity === 'critical' ? 'bg-red-500/20 text-red-400' : ($alert->severity === 'warning' ? 'bg-amber-500/20 text-amber-400' : 'bg-blue-500/20 text-blue-400') }}">{{ __('cashcore.severity_' . $alert->severity) }}</span>
                    <div>
                        <p class="text-white font-medium text-sm">{{ $alert->title }}</p>
                        <p class="text-gray-400 text-xs">{{ $alert->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('cashcore.behavior.alert.read', $alert) }}">@csrf @method('PUT')
                        <button type="submit" class="px-3 py-1.5 border border-white/10 text-gray-400 hover:text-white text-xs font-medium rounded-lg">{{ __('cashcore.mark_read') }}</button>
                    </form>
                    <form method="POST" action="{{ route('cashcore.behavior.alert.dismiss', $alert) }}">@csrf @method('PUT')
                        <button type="submit" class="px-3 py-1.5 text-gray-500 hover:text-gray-300 text-xs">{{ __('cashcore.dismiss') }}</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Schedule New Review --}}
<div class="grid lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.schedule_review') }}</h3>
        <form method="POST" action="{{ route('cashcore.behavior.schedule') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.review_type') }}</label>
                <select name="review_type" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="monthly">{{ __('cashcore.review_monthly') }}</option>
                    <option value="quarterly">{{ __('cashcore.review_quarterly') }}</option>
                    <option value="annual">{{ __('cashcore.review_annual') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.scheduled_date') }}</label>
                <input type="date" name="scheduled_date" value="{{ now()->addDays(7)->format('Y-m-d') }}" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-all">{{ __('cashcore.schedule_review') }}</button>
        </form>
    </div>

    {{-- Pending Reviews --}}
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.pending_reviews') }}</h3>
        @if($pendingReviews->isNotEmpty())
            <div class="space-y-3">
                @foreach($pendingReviews as $review)
                    <div class="flex items-center justify-between p-3 bg-gray-800/30 rounded-xl">
                        <div>
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-500/20 text-blue-400">{{ __('cashcore.review_' . $review->review_type) }}</span>
                            <span class="text-gray-400 text-sm ml-2">{{ $review->scheduled_date->format('d.m.Y') }}</span>
                        </div>
                        <a href="{{ route('cashcore.behavior.review', $review) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-medium rounded-lg">{{ __('cashcore.start_review') }}</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">{{ __('cashcore.no_data') }}</p>
        @endif
    </div>
</div>

{{-- Completed Reviews --}}
@if($completedReviews->isNotEmpty())
    <h3 class="text-lg font-semibold text-white mb-4">{{ __('cashcore.completed_reviews') }}</h3>
    <div class="space-y-2">
        @foreach($completedReviews as $review)
            <div class="bg-gray-900/30 border border-white/5 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500/20 text-emerald-400">{{ __('cashcore.review_' . $review->review_type) }}</span>
                    <span class="text-gray-400 text-sm">{{ $review->completed_date->format('d.m.Y') }}</span>
                </div>
                <span class="text-emerald-400 text-sm font-medium">Streak: {{ $review->streak_count }}</span>
            </div>
        @endforeach
    </div>
@endif
@endsection
