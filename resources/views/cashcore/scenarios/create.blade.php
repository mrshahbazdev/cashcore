@extends('cashcore.layout')

@section('cashcore_content')
<h2 class="text-2xl font-bold text-white mb-6">{{ __('cashcore.create_scenario') }}</h2>

<div class="max-w-2xl">
    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6">
        <form method="POST" action="{{ route('cashcore.scenarios.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.scenario_name') }}</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500" placeholder="e.g. Cut 10% costs">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">{{ __('cashcore.description') }}</label>
                <textarea name="description" rows="2" class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white outline-none focus:ring-2 focus:ring-emerald-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-6 p-5 bg-gray-800/30 rounded-xl">
                <div>
                    <h4 class="text-sm font-medium text-gray-400 mb-3">{{ __('cashcore.before') }}</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">{{ __('cashcore.current_revenue') }}</label>
                            <input type="number" step="0.01" name="current_revenue" value="{{ old('current_revenue', $currentRevenue) }}" required class="w-full px-3 py-2 bg-gray-800/50 border border-white/10 rounded-lg text-white text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">{{ __('cashcore.current_costs') }}</label>
                            <input type="number" step="0.01" name="current_costs" value="{{ old('current_costs', $currentCosts) }}" required class="w-full px-3 py-2 bg-gray-800/50 border border-white/10 rounded-lg text-white text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-cyan-400 mb-3">{{ __('cashcore.after') }}</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">{{ __('cashcore.adjusted_revenue') }}</label>
                            <input type="number" step="0.01" name="adjusted_revenue" value="{{ old('adjusted_revenue', $currentRevenue) }}" required class="w-full px-3 py-2 bg-gray-800/50 border border-white/10 rounded-lg text-white text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">{{ __('cashcore.adjusted_costs') }}</label>
                            <input type="number" step="0.01" name="adjusted_costs" value="{{ old('adjusted_costs', $currentCosts) }}" required class="w-full px-3 py-2 bg-gray-800/50 border border-white/10 rounded-lg text-white text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-semibold rounded-xl transition-all">{{ __('cashcore.save') }}</button>
                <a href="{{ route('cashcore.scenarios.index') }}" class="px-6 py-3 border border-white/10 text-gray-300 hover:text-white rounded-xl transition-all">{{ __('cashcore.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
