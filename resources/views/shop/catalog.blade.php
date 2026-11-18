@extends('layouts.app')

@section('title', 'Katalog Produk — AY SHOP')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900">Katalog Pakaian</h1>
            <p class="text-sm text-zinc-500 mt-1">Menampilkan {{ $products->total() }} produk pakaian pilihan</p>
        </div>

        <form action="{{ route('shop.catalog') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
            <select name="category" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl bg-white border border-stone-200 text-sm focus:outline-none focus:border-zinc-900">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="seller" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl bg-white border border-stone-200 text-sm focus:outline-none focus:border-zinc-900">
                <option value="">Semua Seller</option>
                @foreach($sellers as $seller)
                    <option value="{{ $seller->slug }}" @selected(request('seller') === $seller->slug)>
                        {{ $seller->store_name }}
                    </option>
                @endforeach
            </select>

            <select name="sort" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl bg-white border border-stone-200 text-sm focus:outline-none focus:border-zinc-900">
                <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                <option value="bestseller" @selected(request('sort') === 'bestseller')>Terlaris</option>
                <option value="cheapest" @selected(request('sort') === 'cheapest')>Harga Terendah</option>
                <option value="expensive" @selected(request('sort') === 'expensive')>Harga Tertinggi</option>
            </select>

            @if(request()->hasAny(['q', 'category', 'seller', 'sort']))
                <a href="{{ route('shop.catalog') }}" class="px-3 py-2 text-xs font-medium text-rose-600 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    @if($products->isEmpty())
        <div class="bg-white border border-stone-200 rounded-2xl p-12 text-center">
            <p class="text-zinc-600 font-medium">Tidak ada produk yang cocok dengan filter Anda.</p>
            <a href="{{ route('shop.catalog') }}" class="inline-block mt-4 px-5 py-2.5 rounded-full bg-zinc-900 text-white text-sm font-medium">Lihat Semua Produk</a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

