@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col">
    {{-- Hero --}}
    <section class="flex-1 flex items-center justify-center px-4 py-32">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-emerald-400 text-sm font-medium mb-8">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                Profit First Financial Intelligence
            </div>

            <h1 class="text-5xl sm:text-7xl font-extrabold text-white leading-tight mb-6">
                {{ __('cashcore.tagline') }}
            </h1>

            <p class="text-xl text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                @if(app()->getLocale() === 'de')
                    Das Tool zwingt dich, dein Geld bewusst zu sehen, zu bewerten und aktiv umzulenken. Basierend auf der Profit-First-Methode.
                @else
                    The tool forces you to consciously see, evaluate, and redirect your money. Based on the Profit First methodology.
                @endif
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('cashcore.dashboard') }}" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-2xl text-lg transition-all shadow-lg shadow-emerald-600/25 hover:shadow-emerald-500/40">
                        {{ __('cashcore.dashboard') }} &rarr;
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-2xl text-lg transition-all shadow-lg shadow-emerald-600/25 hover:shadow-emerald-500/40">
                        @if(app()->getLocale() === 'de') Jetzt starten @else Get Started @endif &rarr;
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 border border-white/10 hover:border-white/20 text-gray-300 hover:text-white font-medium rounded-2xl text-lg transition-all">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Features Grid --}}
    <section class="py-24 px-4 border-t border-white/5">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-white text-center mb-16">
                @if(app()->getLocale() === 'de') 7 Kernfunktionen @else 7 Core Features @endif
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $features = [
                        ['icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'title_en' => 'Cash Transparency', 'title_de' => 'Cash Transparenz', 'desc_en' => 'See where your money really goes. KPIs: Cost %, Profit %, Overhead %.', 'desc_de' => 'Sieh, wohin dein Geld wirklich fließt. KPIs: Kosten %, Gewinn %, Overhead %.', 'color' => 'emerald'],
                        ['icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z', 'title_en' => 'Leak Detection', 'title_de' => 'Leck-Erkennung', 'desc_en' => 'Automatically find rising costs, unused subscriptions, dead expenses.', 'desc_de' => 'Automatisch steigende Kosten, ungenutzte Abos, tote Ausgaben finden.', 'color' => 'red'],
                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title_en' => 'Expense Scoring', 'title_de' => 'Kosten-Scoring', 'desc_en' => 'Rate every expense. Keep, Reduce, or Eliminate.', 'desc_de' => 'Bewerte jede Ausgabe. Behalten, Reduzieren oder Eliminieren.', 'color' => 'amber'],
                        ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title_en' => 'Cash Unlocker', 'title_de' => 'Cash Unlocker', 'desc_en' => 'Find hidden capital in open invoices, inventory, payment terms.', 'desc_de' => 'Finde verborgenes Kapital in offenen Rechnungen, Lager, Zahlungszielen.', 'color' => 'purple'],
                        ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title_en' => 'Behavioral System', 'title_de' => 'Verhaltens-System', 'desc_en' => 'Monthly reviews, alerts, streaks. Change behavior permanently.', 'desc_de' => 'Monatliche Reviews, Warnungen, Serien. Verhalten dauerhaft ändern.', 'color' => 'blue'],
                        ['icon' => 'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z', 'title_en' => 'Scenario Simulator', 'title_de' => 'Szenario-Simulator', 'desc_en' => 'What if you cut 10% costs? See the profit impact instantly.', 'desc_de' => 'Was wenn du 10% Kosten senkst? Sieh den Gewinneffekt sofort.', 'color' => 'cyan'],
                        ['icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z', 'title_en' => 'Profit Allocation', 'title_de' => 'Gewinnverteilung', 'desc_en' => 'Automatic Profit First distribution: Profit, Taxes, Salary, Operations.', 'desc_de' => 'Automatische Profit-First-Verteilung: Gewinn, Steuern, Gehalt, Betrieb.', 'color' => 'teal'],
                    ];
                @endphp
                @foreach($features as $f)
                    <div class="bg-gray-900/50 border border-white/5 rounded-2xl p-6 hover:border-{{ $f['color'] }}-500/30 transition-all group">
                        <div class="w-12 h-12 bg-{{ $f['color'] }}-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-{{ $f['color'] }}-500/20 transition-colors">
                            <svg class="w-6 h-6 text-{{ $f['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">{{ app()->getLocale() === 'de' ? $f['title_de'] : $f['title_en'] }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ app()->getLocale() === 'de' ? $f['desc_de'] : $f['desc_en'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 px-4 border-t border-white/5 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} CashCore. Profit First Financial Intelligence.
    </footer>
</div>
@endsection
