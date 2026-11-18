@extends('layouts.seller')

@section('title', 'Dashboard Seller — AY SHOP')
@section('header', 'Dashboard Toko')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-stone-200 rounded-2xl p-5">
            <p class="text-xs font-medium text-zinc-500">Total Pendapatan</p>
            <p class="text-2xl font-bold text-zinc-900 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-emerald-600 mt-2">Dari pesanan aktif &amp; selesai</p>
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl p-5">
            <p class="text-xs font-medium text-zinc-500">Pesanan Perlu Diproses</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $pendingOrdersCount }}</p>
            <a href="{{ route('seller.orders') }}" class="text-xs text-zinc-600 hover:underline mt-2 inline-block">Kelola Pesanan &rarr;</a>
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl p-5">
            <p class="text-xs font-medium text-zinc-500">Produk Aktif</p>
            <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $activeProducts }} <span class="text-sm font-normal text-zinc-400">/ {{ $totalProducts }}</span></p>
            <a href="{{ route('seller.products.index') }}" class="text-xs text-zinc-600 hover:underline mt-2 inline-block">Lihat Semua Produk &rarr;</a>
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl p-5">
            <p class="text-xs font-medium text-zinc-500">Total Pakaian Terjual</p>
            <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $totalSold }} <span class="text-sm font-normal text-zinc-400">pcs</span></p>
            <p class="text-xs text-zinc-500 mt-2">Rating Toko: {{ number_format($seller->rating, 1) }}/5</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Recent Order Items -->
        <div class="lg:col-span-7 bg-white border border-stone-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-base text-zinc-900">Pesanan Masuk Terbaru</h2>
                <a href="{{ route('seller.orders') }}" class="text-xs font-semibold text-amber-700 hover:underline">Lihat Semua</a>
            </div>

            @if($recentOrderItems->isEmpty())
                <p class="text-sm text-zinc-500 py-8 text-center">Belum ada pesanan masuk untuk toko Anda.</p>
            @else
                <div class="divide-y divide-stone-100">
                    @foreach($recentOrderItems as $item)
                        <div class="py-3 flex items-center justify-between gap-4 text-sm">
                            <div>
                                <p class="font-semibold text-zinc-900">{{ $item->product_name }}</p>
                                <p class="text-xs text-zinc-500">
                                    #{{ $item->order->order_number }} &bull; {{ $item->order->customer_name }} &bull; {{ $item->size }}, {{ $item->color }} &times; {{ $item->quantity }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-semibold text-zinc-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-semibold bg-stone-100 text-zinc-700">
                                    {{ \App\Models\Order::statusLabel($item->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Top Products -->
        <div class="lg:col-span-5 bg-white border border-stone-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-base text-zinc-900">Produk Terlaris</h2>
                <a href="{{ route('seller.products.create') }}" class="px-3 py-1.5 rounded-lg bg-zinc-900 text-white text-xs font-semibold">+ Tambah Produk</a>
            </div>

            @if($topProducts->isEmpty())
                <p class="text-sm text-zinc-500 py-8 text-center">Belum ada produk.</p>
            @else
                <div class="space-y-3">
                    @foreach($topProducts as $product)
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-11 h-11 rounded-xl object-cover bg-stone-100 shrink-0">
                                <div class="min-w-0">
                                    <p class="font-semibold text-sm text-zinc-900 truncate">{{ $product->name }}</p>
                                    <p class="text-xs text-zinc-500">Stok: {{ $product->stock }} &bull; Terjual: {{ $product->sold_count }}</p>
                                </div>
                            </div>
                            <a href="{{ route('seller.products.edit', $product) }}" class="text-xs font-medium text-amber-700 hover:underline shrink-0">Edit</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

