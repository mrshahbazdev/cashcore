@extends('cashcore.layout')

@section('cashcore_content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-white">{{ __('cashcore.transactions') }}</h2>
    <div class="flex items-center gap-3">
        <a href="{{ route('cashcore.transactions.import') }}" class="px-4 py-2 border border-white/10 text-gray-300 hover:text-white text-sm font-medium rounded-xl transition-all">{{ __('cashcore.import_csv') }}</a>
        <a href="{{ route('cashcore.transactions.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-xl transition-all">+ {{ __('cashcore.add_transaction') }}</a>
    </div>
</div>

{{-- Filter --}}
<div class="flex gap-2 mb-6">
    @foreach(['all' => __('cashcore.filter_all'), 'income' => __('cashcore.filter_income'), 'expense' => __('cashcore.filter_expense')] as $key => $label)
        <a href="{{ route('cashcore.transactions.index', ['filter' => $key]) }}" class="px-4 py-2 text-sm font-medium rounded-xl transition-all {{ $filter === $key ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'text-gray-400 hover:text-white border border-white/10' }}">{{ $label }}</a>
    @endforeach
</div>

<div class="bg-gray-900/50 border border-white/5 rounded-2xl overflow-hidden">
    @if($transactions->isNotEmpty())
        <table class="w-full text-sm">
            <thead><tr class="text-gray-400 border-b border-white/5">
                <th class="text-left py-3 px-4">{{ __('cashcore.date') }}</th>
                <th class="text-left py-3 px-4">{{ __('cashcore.description') }}</th>
                <th class="text-left py-3 px-4">{{ __('cashcore.category') }}</th>
                <th class="text-left py-3 px-4">{{ __('cashcore.type') }}</th>
                <th class="text-right py-3 px-4">{{ __('cashcore.amount') }}</th>
                <th class="text-right py-3 px-4">{{ __('cashcore.actions') }}</th>
            </tr></thead>
            <tbody>
                @foreach($transactions as $tx)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-3 px-4 text-gray-400">{{ $tx->transaction_date->format('d.m.Y') }}</td>
                        <td class="py-3 px-4 text-white">
                            {{ $tx->description }}
                            @if($tx->is_recurring) <span class="ml-1 text-xs text-amber-400">({{ __('cashcore.recurring') }})</span> @endif
                        </td>
                        <td class="py-3 px-4 text-gray-400">{{ $tx->category?->icon }} {{ $tx->category?->name ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $tx->type === 'income' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">{{ __('cashcore.' . $tx->type) }}</span>
                        </td>
                        <td class="py-3 px-4 text-right font-medium {{ $tx->type === 'income' ? 'text-emerald-400' : 'text-red-400' }}">&euro; {{ number_format($tx->amount, 2) }}</td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('cashcore.transactions.edit', $tx) }}" class="text-gray-400 hover:text-white text-xs mr-2">{{ __('cashcore.edit') }}</a>
                            <form method="POST" action="{{ route('cashcore.transactions.destroy', $tx) }}" class="inline" onsubmit="return confirm('{{ __('cashcore.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs">{{ __('cashcore.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $transactions->appends(request()->query())->links() }}</div>
    @else
        <div class="p-8 text-center text-gray-500">{{ __('cashcore.no_data') }}</div>
    @endif
</div>
@endsection
