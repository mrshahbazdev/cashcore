@extends('cashcore.layout')

@section('cashcore_content')
<h2 class="text-2xl font-bold text-white mb-6">{{ __('cashcore.review_checklist') }}</h2>

<div class="max-w-2xl">
    <div class="bg-gray-900/50 border border-blue-500/20 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
            <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-500/20 text-blue-400">{{ __('cashcore.review_' . $review->review_type) }}</span>
            <span class="text-gray-400 text-sm">{{ $review->scheduled_date->format('d.m.Y') }}</span>
        </div>

        <form method="POST" action="{{ route('cashcore.behavior.complete', $review) }}" class="space-y-4">
            @csrf
            @php $checklist = $review->checklist ?? \App\Models\CashReview::getDefaultChecklist(); @endphp
            @foreach($checklist as $i => $item)
                <label class="flex items-center gap-3 p-3 bg-gray-800/30 rounded-xl hover:bg-gray-800/50 transition-colors cursor-pointer">
                    <input type="checkbox" name="checklist[{{ $i }}][done]" value="1" {{ ($item['done'] ?? false) ? 'checked' : '' }} class="rounded border-gray-600 bg-gray-800 text-emerald-500 focus:ring-emerald-500">
                    <input type="hidden" name="checklist[{{ $i }}][task]" value="{{ $item['task'] }}">
                    <span class="text-gray-300 text-sm">{{ $item['task'] }}</span>
                </label>
            @endforeach

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.notes') }}</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">{{ $review->notes }}</textarea>
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-all">{{ __('cashcore.complete_review') }}</button>
        </form>
    </div>
</div>
@endsection
