@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-32">
    <div class="w-full max-w-md">
        <div class="bg-gray-900/50 border border-white/10 rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-white mb-6 text-center">Login</h2>
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-500/20 border border-red-500/30 rounded-xl text-red-400 text-sm">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-gray-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-600 bg-gray-800 text-emerald-500 focus:ring-emerald-500">
                    <label for="remember" class="text-sm text-gray-400">Remember me</label>
                </div>
                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-all">Login</button>
            </form>
            <p class="mt-6 text-center text-sm text-gray-400">
                @if(app()->getLocale() === 'de') Noch kein Konto? @else Don't have an account? @endif
                <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300">Register</a>
            </p>
        </div>
    </div>
</div>
@endsection
