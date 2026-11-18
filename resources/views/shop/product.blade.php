@extends('layouts.app')

@section('title', $product->name . ' — AY SHOP')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="text-xs text-zinc-500 mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-zinc-900">Beranda</a>
        <span>/</span>
        <a href="{{ route('shop.catalog', ['category' => $product->category->slug]) }}" class="hover:text-zinc-900">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-zinc-900 font-medium truncate">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        <!-- Product Image -->
        <div class="lg:col-span-6">
            <div class="bg-white border border-stone-200 rounded-3xl overflow-hidden aspect-square">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-stone-400">No Image</div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="lg:col-span-6 flex flex-col justify-between">
            <div class="space-y-5">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-stone-200/70 text-zinc-700 text-xs font-medium">{{ $product->category->name }}</span>
                    @if($product->discount_percent > 0)
                        <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-xs font-bold">Hemat {{ $product->discount_percent }}%</span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 leading-snug">{{ $product->name }}</h1>

                <div class="flex items-center gap-4 text-sm text-zinc-500">
                    <span>Terjual <strong class="text-zinc-900">{{ $product->sold_count }}</strong></span>
                    <span>&bull;</span>
                    <span>Stok <strong class="text-zinc-900">{{ $product->stock }}</strong> pcs</span>
                    @if($product->material)
                        <span>&bull;</span>
                        <span>Bahan: <strong class="text-zinc-900">{{ $product->material }}</strong></span>
                    @endif
                </div>

                <div class="py-4 border-y border-stone-200">
                    <p class="text-3xl font-bold text-zinc-900">
                        Rp {{ number_format($product->effective_price, 0, ',', '.') }}
                    </p>
                    @if($product->discount_percent > 0)
                        <p class="text-sm text-zinc-400 line-through mt-1">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    @endif
                </div>

                <!-- Add to Cart Form -->
                <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-5">
                    @csrf

                    @if(!empty($product->sizes))
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 mb-2">Pilih Ukuran</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->sizes as $i => $size)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="size" value="{{ $size }}" class="peer sr-only" @checked($i === 0)>
                                        <span class="inline-block px-4 py-2 rounded-xl border border-stone-300 text-sm font-medium peer-checked:bg-zinc-900 peer-checked:text-white peer-checked:border-zinc-900 hover:border-zinc-900 transition">
                                            {{ $size }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($product->colors))
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 mb-2">Pilih Warna</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->colors as $i => $color)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color" value="{{ $color }}" class="peer sr-only" @checked($i === 0)>
                                        <span class="inline-block px-4 py-2 rounded-xl border border-stone-300 text-sm font-medium peer-checked:bg-zinc-900 peer-checked:text-white peer-checked:border-zinc-900 hover:border-zinc-900 transition">
                                            {{ $color }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-zinc-600 mb-1.5">Jumlah</label>
                            <input type="number" name="quantity" value="1" min="1" max="{{ max(1, $product->stock) }}"
                                   class="w-24 px-3.5 py-2.5 rounded-xl border border-stone-300 bg-white text-sm font-semibold focus:outline-none focus:border-zinc-900">
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit" @disabled($product->stock < 1)
                                class="flex-1 min-w-[180px] px-6 py-3.5 rounded-full bg-zinc-900 text-white font-semibold text-sm hover:bg-zinc-800 disabled:opacity-40 transition cursor-pointer">
                            {{ $product->stock < 1 ? 'Stok Habis' : '+ Tambah ke Keranjang' }}
                        </button>
                        <button type="submit" name="buy_now" value="1" @disabled($product->stock < 1)
                                class="px-6 py-3.5 rounded-full bg-amber-400 text-zinc-900 font-semibold text-sm hover:bg-amber-300 disabled:opacity-40 transition cursor-pointer">
                            Beli Langsung
                        </button>
                    </div>
                </form>

                <!-- Description -->
                <div class="pt-4">
                    <h3 class="font-semibold text-sm text-zinc-900 mb-2">Deskripsi Produk</h3>
                    <p class="text-sm text-zinc-600 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                </div>

                <!-- Seller Card -->
                <div class="bg-white border border-stone-200 rounded-2xl p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-zinc-900 text-amber-300 font-bold flex items-center justify-center text-sm shrink-0">
                            {{ strtoupper(substr($product->seller->store_name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('shop.seller', $product->seller->slug) }}" class="font-bold text-sm text-zinc-900 hover:underline">
                                    {{ $product->seller->store_name }}
                                </a>
                                @if($product->seller->is_verified)
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-semibold">Verified</span>
                                @endif
                            </div>
                            <p class="text-xs text-zinc-500">{{ $product->seller->city }} &bull; Rating {{ number_format($product->seller->rating, 1) }}/5</p>
                        </div>
                    </div>
                    <a href="{{ route('shop.seller', $product->seller->slug) }}" class="px-4 py-2 rounded-xl border border-stone-200 text-xs font-semibold text-zinc-700 hover:bg-stone-100 transition shrink-0">
                        Kunjungi Toko
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <section class="mt-16">
            <h2 class="text-xl font-bold text-zinc-900 mb-6">Produk Serupa</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

