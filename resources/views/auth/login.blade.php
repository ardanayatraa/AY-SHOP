@extends('layouts.app')

@section('title', 'Masuk — AY SHOP')

@section('content')
<div class="max-w-md mx-auto px-4 py-14">
    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-zinc-900">Masuk ke AY SHOP</h1>
            <p class="text-sm text-zinc-500 mt-1">Masuk sebagai Pembeli atau Seller</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kata Sandi</label>
                <input type="password" name="password" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-zinc-600">
                    <input type="checkbox" name="remember" class="rounded border-stone-300">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="w-full py-3 rounded-full bg-zinc-900 text-white font-semibold text-sm hover:bg-zinc-800 transition cursor-pointer">
                Masuk
            </button>
        </form>

        <p class="text-center text-xs text-zinc-500 mt-5">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-zinc-900 hover:underline">Daftar sekarang</a>
        </p>

        <!-- Demo Credentials Helper -->
        <div class="mt-6 pt-5 border-t border-stone-100">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 mb-2">Akun Demo Seeder (Password: <code class="text-zinc-900">password</code>)</p>
            <div class="grid grid-cols-1 gap-1.5 text-xs text-zinc-600 bg-stone-50 p-3 rounded-xl border border-stone-200">
                <div><strong>Seller 1 (Nusantara Apparel):</strong> seller1@ayshop.id</div>
                <div><strong>Seller 2 (Batik &amp; Linen):</strong> seller2@ayshop.id</div>
                <div><strong>Seller 3 (Urban Outerwear):</strong> seller3@ayshop.id</div>
                <div><strong>Seller 4 (Aura Modest):</strong> seller4@ayshop.id</div>
                <div class="pt-1 border-t border-stone-200"><strong>Pembeli:</strong> budi@ayshop.id</div>
            </div>
        </div>
    </div>
</div>
@endsection

