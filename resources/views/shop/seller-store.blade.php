@extends('layouts.app')

@section('title', $seller->store_name . ' — AY SHOP')

@section('content')
<div class="bg-zinc-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-amber-400 text-zinc-900 font-bold flex items-center justify-center text-2xl shrink-0">
                {{ strtoupper(substr($seller->store_name, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $seller->store_name }}</h1>
                    @if($seller->is_verified)
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold">
                            Verified
                        </span>
                    @endif
                </div>
                <p class="text-sm text-stone-300 mt-1">{{ $seller->city }} &bull; Rating {{ number_format($seller->rating, 1) }}/5 &bull; {{ $products->total() }} Produk</p>
                <p class="text-sm text-stone-400 mt-2 max-w-2xl">{{ $seller->description }}</p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if($products->isEmpty())
        <div class="bg-white border border-stone-200 rounded-2xl p-12 text-center text-zinc-500">
            Toko ini belum memiliki produk aktif.
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

