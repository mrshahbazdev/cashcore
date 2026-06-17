@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
        {{-- Top Bar --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <span class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-lg font-bold">$</span>
                    {{ __('cashcore.title') }}
                </h1>
                <p class="text-gray-400 mt-1">{{ __('cashcore.tagline') }}</p>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <nav class="flex flex-wrap gap-2 mb-8 p-1 bg-gray-900/50 rounded-2xl border border-white/5">
            @php
                $tabs = [
                    ['route' => 'cashcore.dashboard', 'label' => __('cashcore.nav_dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'cashcore.transactions.index', 'label' => __('cashcore.nav_transactions'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['route' => 'cashcore.leaks.index', 'label' => __('cashcore.nav_leaks'), 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z'],
                    ['route' => 'cashcore.scoring.index', 'label' => __('cashcore.nav_scoring'), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['route' => 'cashcore.unlocker.index', 'label' => __('cashcore.nav_unlocker'), 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                    ['route' => 'cashcore.behavior.index', 'label' => __('cashcore.nav_behavior'), 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                    ['route' => 'cashcore.scenarios.index', 'label' => __('cashcore.nav_scenarios'), 'icon' => 'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
                    ['route' => 'cashcore.allocation.index', 'label' => __('cashcore.nav_allocation'), 'icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <a href="{{ route($tab['route']) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl transition-all {{ request()->routeIs($tab['route'] . '*') ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/></svg>
                    <span class="hidden sm:inline">{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-xl text-red-400 text-sm">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        @yield('cashcore_content')
    </div>
</div>
@endsection
