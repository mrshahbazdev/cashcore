@extends('cashcore.layout')

@section('cashcore_content')
<h2 class="text-2xl font-bold text-white mb-6">{{ __('cashcore.add_blocker') }}</h2>

<div class="max-w-2xl">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <form method="POST" action="{{ route('cashcore.unlocker.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.blocker_type') }}</label>
                    <select name="blocker_type" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="open_invoice">{{ __('cashcore.open_invoice') }}</option>
                        <option value="payment_terms">{{ __('cashcore.payment_terms') }}</option>
                        <option value="inventory">{{ __('cashcore.inventory') }}</option>
                        <option value="inefficient_flow">{{ __('cashcore.inefficient_flow') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.blocked_amount') }} (&euro;)</label>
                    <input type="number" step="0.01" name="blocked_amount" value="{{ old('blocked_amount') }}" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.description') }}</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.debtor_name') }}</label>
                    <input type="text" name="debtor_name" value="{{ old('debtor_name') }}" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.due_date') }}</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.days_overdue') }}</label>
                <input type="number" name="days_overdue" value="{{ old('days_overdue', 0) }}" min="0" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.notes') }}</label>
                <textarea name="description" rows="2" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">{{ old('description') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-xl transition-all">{{ __('cashcore.save') }}</button>
                <a href="{{ route('cashcore.unlocker.index') }}" class="px-6 py-3 border border-white/10 text-gray-300 hover:text-white rounded-xl transition-all">{{ __('cashcore.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
