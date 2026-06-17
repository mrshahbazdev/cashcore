@extends('cashcore.layout')

@section('cashcore_content')
<h2 class="text-2xl font-bold text-white mb-6">{{ __('cashcore.edit_blocker') }}</h2>

<div class="max-w-2xl">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <form method="POST" action="{{ route('cashcore.unlocker.update', $blocker) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.blocker_type') }}</label>
                    <select name="blocker_type" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach(['open_invoice', 'payment_terms', 'inventory', 'inefficient_flow'] as $t)
                            <option value="{{ $t }}" {{ $blocker->blocker_type === $t ? 'selected' : '' }}>{{ __('cashcore.' . $t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.blocked_amount') }} (&euro;)</label>
                    <input type="number" step="0.01" name="blocked_amount" value="{{ old('blocked_amount', $blocker->blocked_amount) }}" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.description') }}</label>
                <input type="text" name="title" value="{{ old('title', $blocker->title) }}" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.debtor_name') }}</label>
                    <input type="text" name="debtor_name" value="{{ old('debtor_name', $blocker->debtor_name) }}" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.due_date') }}</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $blocker->due_date?->format('Y-m-d')) }}" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.status') }}</label>
                <select name="status" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                    @foreach(['active', 'in_progress', 'resolved'] as $s)
                        <option value="{{ $s }}" {{ $blocker->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-xl transition-all">{{ __('cashcore.save') }}</button>
                <a href="{{ route('cashcore.unlocker.index') }}" class="px-6 py-3 border border-white/10 text-gray-300 hover:text-white rounded-xl transition-all">{{ __('cashcore.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
