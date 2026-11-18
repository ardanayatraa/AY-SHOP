@extends('layouts.app')

@section('title', 'AY SHOP — Toko Pakaian Online Multi-Seller')

@section('content')
    <!-- Hero Section -->
    <section class="bg-zinc-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7 space-y-6">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/15 text-amber-300 border border-amber-400/30 text-xs font-semibold tracking-wide">
                    Koleksi Terbaru &bull; Brand Lokal Pilihan
                </span>
                <h1 class="text-3xl sm:text-5xl font-bold tracking-tight leading-tight">
                    Gaya Pakaian Terbaik dari Seller Terpercaya di <span class="text-amber-300">AY SHOP</span>.
                </h1>
                <p class="text-stone-300 text-base sm:text-lg max-w-xl leading-relaxed">
                    Temukan kaos heavyweight, kemeja linen, jaket denim, hingga gamis elegan langsung dari brand lokal Bandung, Yogyakarta, Jakarta, dan Surabaya.
                </p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ route('shop.catalog') }}" class="px-6 py-3 rounded-full bg-white text-zinc-900 font-semibold text-sm hover:bg-stone-200 transition">
                        Belanja Sekarang
                    </a>
                    <a href="{{ route('shop.sellers') }}" class="px-6 py-3 rounded-full border border-zinc-700 text-stone-200 font-semibold text-sm hover:bg-zinc-800 transition">
                        Jelajahi Seller
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                <div class="bg-zinc-800/80 border border-zinc-700/70 rounded-2xl p-5 space-y-2">
                    <p class="text-3xl font-bold text-amber-300">{{ $sellers->count() }}+</p>
                    <p class="text-sm font-medium text-white">Toko Seller Resmi</p>
                    <p class="text-xs text-stone-400">Terverifikasi dari berbagai kota mode Indonesia.</p>
                </div>
                <div class="bg-zinc-800/80 border border-zinc-700/70 rounded-2xl p-5 space-y-2">
                    <p class="text-3xl font-bold text-emerald-300">100%</p>
                    <p class="text-sm font-medium text-white">Kualitas Lokal</p>
                    <p class="text-xs text-stone-400">Bahan pilihan seperti Combed 24s &amp; Cotton Linen.</p>
                </div>
                <div class="col-span-2 bg-gradient-to-r from-amber-500/20 to-rose-500/10 border border-amber-400/20 rounded-2xl p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-white">Punya Brand Pakaian Sendiri?</p>
                        <p class="text-xs text-stone-300 mt-0.5">Kelola produk &amp; pesanan lewat Menu Seller AY SHOP.</p>
                    </div>
                    <a href="{{ auth()->check() && auth()->user()->isSeller() ? route('seller.dashboard') : route('login') }}"
                       class="shrink-0 px-4 py-2 rounded-full bg-amber-400 text-zinc-900 text-xs font-bold hover:bg-amber-300 transition">
                        Menu Seller
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-zinc-900">Kategori Pilihan</h2>
            <a href="{{ route('shop.catalog') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">Lihat Semua &rarr;</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($categories as $category)
                <a href="{{ route('shop.catalog', ['category' => $category->slug]) }}"
                   class="bg-white border border-stone-200 rounded-2xl p-4 hover:border-zinc-900 hover:shadow-sm transition text-center group">
                    <p class="font-semibold text-sm text-zinc-900 group-hover:text-amber-700 transition">{{ $category->name }}</p>
                    <p class="text-xs text-zinc-500 mt-1">{{ $category->products_count }} produk</p>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Featured Products -->
    @if($featuredProducts->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-14">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-zinc-900">Produk Unggulan</h2>
                    <p class="text-sm text-zinc-500">Pilihan favorit pembeli minggu ini</p>
                </div>
                <a href="{{ route('shop.catalog', ['sort' => 'bestseller']) }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">Terlaris &rarr;</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- Official Sellers -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-zinc-900">Seller Terpercaya</h2>
                <p class="text-sm text-zinc-500">Kunjungi toko resmi untuk melihat koleksi lengkap masing-masing brand</p>
            </div>
            <a href="{{ route('shop.sellers') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">Semua Toko &rarr;</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($sellers as $seller)
                <a href="{{ route('shop.seller', $seller->slug) }}"
                   class="bg-white border border-stone-200 rounded-2xl p-5 hover:border-zinc-900 hover:shadow-sm transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="w-11 h-11 rounded-xl bg-zinc-900 text-amber-300 font-bold flex items-center justify-center text-sm">
                                {{ strtoupper(substr($seller->store_name, 0, 2)) }}
                            </div>
                            @if($seller->is_verified)
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-semibold">
                                    Verified
                                </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-zinc-900">{{ $seller->store_name }}</h3>
                        <p class="text-xs text-zinc-500 mt-0.5">{{ $seller->city }} &bull; Rating {{ number_format($seller->rating, 1) }}/5</p>
                        <p class="text-xs text-zinc-600 mt-2 line-clamp-2 leading-relaxed">{{ $seller->description }}</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between text-xs font-medium text-zinc-700">
                        <span>{{ $seller->products_count }} Produk</span>
                        <span class="text-amber-700">Kunjungi Toko &rarr;</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Latest Products -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-zinc-900">Koleksi Terbaru</h2>
                <p class="text-sm text-zinc-500">Update pakaian terbaru dari seluruh seller AY SHOP</p>
            </div>
            <a href="{{ route('shop.catalog') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">Lihat Semua &rarr;</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($latestProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
@endsection

