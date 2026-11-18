@extends('layouts.app')

@section('title', 'Daftar Toko Seller — AY SHOP')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900">Toko Seller Resmi AY SHOP</h1>
            <p class="text-sm text-zinc-500 mt-1">Pilih toko favorit Anda untuk melihat koleksi pakaian terbaik</p>
        </div>
        <a href="{{ auth()->check() && auth()->user()->isSeller() ? route('seller.dashboard') : route('seller.register') }}"
           class="px-5 py-2.5 rounded-full bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition self-start">
            + Buka Toko / Menu Seller
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($sellers as $seller)
            <a href="{{ route('shop.seller', $seller->slug) }}"
               class="bg-white border border-stone-200 rounded-2xl p-6 hover:border-zinc-900 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-zinc-900 text-amber-300 font-bold flex items-center justify-center text-lg">
                            {{ strtoupper(substr($seller->store_name, 0, 2)) }}
                        </div>
                        @if($seller->is_verified)
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                                Verified Seller
                            </span>
                        @endif
                    </div>
                    <h2 class="text-lg font-bold text-zinc-900">{{ $seller->store_name }}</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">{{ $seller->city }} &bull; Rating ★ {{ number_format($seller->rating, 1) }}</p>
                    <p class="text-sm text-zinc-600 mt-3 leading-relaxed">{{ $seller->description }}</p>
                </div>
                <div class="mt-6 pt-4 border-t border-stone-100 flex items-center justify-between text-sm">
                    <span class="font-medium text-zinc-700">{{ $seller->products_count }} Produk Aktif</span>
                    <span class="font-semibold text-amber-700">Lihat Toko &rarr;</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection

