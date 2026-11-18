@props(['product'])

<div class="group bg-white rounded-2xl border border-stone-200 overflow-hidden hover:shadow-md hover:border-stone-300 transition flex flex-col">
    <a href="{{ route('shop.product', $product->slug) }}" class="relative aspect-square bg-stone-100 overflow-hidden block">
        @if($product->image)
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-stone-400 text-sm">No Image</div>
        @endif

        @if($product->discount_percent > 0)
            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-rose-600 text-white text-[11px] font-bold shadow-sm">
                -{{ $product->discount_percent }}%
            </span>
        @endif

        @if($product->stock < 1)
            <div class="absolute inset-0 bg-zinc-900/50 flex items-center justify-center">
                <span class="px-3 py-1 rounded-full bg-white text-zinc-900 text-xs font-bold">Stok Habis</span>
            </div>
        @endif
    </a>

    <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between gap-2 text-[11px] text-zinc-500 mb-1">
                <span class="truncate">{{ $product->category->name ?? 'Pakaian' }}</span>
                <span class="shrink-0">{{ $product->sold_count }} terjual</span>
            </div>
            <a href="{{ route('shop.product', $product->slug) }}" class="font-semibold text-sm text-zinc-900 hover:text-amber-700 transition line-clamp-2 leading-snug">
                {{ $product->name }}
            </a>
            <a href="{{ route('shop.seller', $product->seller->slug) }}" class="inline-flex items-center gap-1 text-xs text-zinc-500 hover:text-zinc-800 mt-1.5">
                <span class="truncate">{{ $product->seller->store_name }}</span>
                @if($product->seller->is_verified)
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block" title="Verified Seller"></span>
                @endif
            </a>
        </div>

        <div class="mt-3 pt-3 border-t border-stone-100 flex items-baseline justify-between gap-2">
            <div>
                <p class="font-bold text-base text-zinc-900">
                    Rp {{ number_format($product->effective_price, 0, ',', '.') }}
                </p>
                @if($product->discount_percent > 0)
                    <p class="text-xs text-zinc-400 line-through">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                @endif
            </div>
            <a href="{{ route('shop.product', $product->slug) }}" class="text-xs font-semibold text-zinc-700 hover:text-zinc-900 px-2.5 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 transition">
                Detail
            </a>
        </div>
    </div>
</div>

