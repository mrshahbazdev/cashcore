<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CashCore') – Profit First Financial Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-gray-100 font-sans antialiased min-h-screen">
    {{-- Header --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-gray-950/90 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <span class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-lg font-bold text-white">$</span>
                    <span class="text-xl font-bold text-white tracking-tight group-hover:text-emerald-400 transition-colors">CashCore</span>
                </a>

                <div class="flex items-center gap-4">
                    {{-- Language Switcher --}}
                    <div class="flex items-center gap-1 bg-gray-800/50 rounded-lg p-0.5">
                        <a href="{{ route('locale.set', 'en') }}" class="px-2.5 py-1 text-xs font-medium rounded-md transition-colors {{ app()->getLocale() === 'en' ? 'bg-emerald-500/20 text-emerald-400' : 'text-gray-400 hover:text-white' }}">EN</a>
                        <a href="{{ route('locale.set', 'de') }}" class="px-2.5 py-1 text-xs font-medium rounded-md transition-colors {{ app()->getLocale() === 'de' ? 'bg-emerald-500/20 text-emerald-400' : 'text-gray-400 hover:text-white' }}">DE</a>
                    </div>

                    @auth
                        <a href="{{ route('cashcore.dashboard') }}" class="px-4 py-2 text-sm font-medium text-emerald-400 hover:text-emerald-300 transition-colors">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl transition-all">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="pt-16">
        @yield('content')
    </main>
</body>
</html>
