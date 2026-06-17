@extends('cashcore.layout')

@section('cashcore_content')
<h2 class="text-2xl font-bold text-white mb-6">{{ __('cashcore.add_transaction') }}</h2>

<div class="max-w-2xl">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <form method="POST" action="{{ route('cashcore.transactions.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.type') }}</label>
                    <select name="type" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="expense">{{ __('cashcore.expense') }}</option>
                        <option value="income">{{ __('cashcore.income') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.amount') }} (&euro;)</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.description') }}</label>
                <input type="text" name="description" value="{{ old('description') }}" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.vendor') }}</label>
                    <input type="text" name="vendor" value="{{ old('vendor') }}" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.category') }}</label>
                    <select name="cash_category_id" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">{{ __('cashcore.select_category') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.transaction_date') }}</label>
                <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_recurring" value="1" class="rounded border-gray-600 bg-gray-800 text-emerald-500 focus:ring-emerald-500">
                    <span class="text-sm text-gray-300">{{ __('cashcore.is_recurring') }}</span>
                </label>
                <select name="recurring_interval" class="px-4 py-2 bg-gray-800/50 border border-white/10 rounded-xl text-white text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">-</option>
                    <option value="monthly">{{ __('cashcore.monthly') }}</option>
                    <option value="quarterly">{{ __('cashcore.quarterly') }}</option>
                    <option value="yearly">{{ __('cashcore.yearly') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.notes') }}</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">{{ old('notes') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-all">{{ __('cashcore.save') }}</button>
                <a href="{{ route('cashcore.transactions.index') }}" class="px-6 py-3 border border-white/10 text-gray-300 hover:text-white rounded-xl transition-all">{{ __('cashcore.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
