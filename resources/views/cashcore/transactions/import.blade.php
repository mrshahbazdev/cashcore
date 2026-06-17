@extends('cashcore.layout')

@section('cashcore_content')
<h2 class="text-2xl font-bold text-white mb-6">{{ __('cashcore.import_csv') }}</h2>

<div class="max-w-2xl">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <p class="text-gray-400 text-sm mb-6">{{ __('cashcore.import_instructions') }}</p>
        <form method="POST" action="{{ route('cashcore.transactions.import.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">CSV File</label>
                <input type="file" name="csv_file" accept=".csv,.txt" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-600 file:text-white hover:file:bg-emerald-500">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-all">{{ __('cashcore.import_csv') }}</button>
                <a href="{{ route('cashcore.transactions.index') }}" class="px-6 py-3 border border-white/10 text-gray-300 hover:text-white rounded-xl transition-all">{{ __('cashcore.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
