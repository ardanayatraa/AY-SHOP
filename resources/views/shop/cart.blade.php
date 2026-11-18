@extends('layouts.app')

@section('title', 'Keranjang Belanja — AY SHOP')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 mb-8">Keranjang Belanja</h1>

    @if(empty($cart))
        <div class="bg-white border border-stone-200 rounded-2xl p-12 text-center">
            <p class="text-lg font-semibold text-zinc-800">Keranjang Anda masih kosong</p>
            <p class="text-sm text-zinc-500 mt-1">Yuk jelajahi koleksi pakaian terbaik dari seller AY SHOP!</p>
            <a href="{{ route('shop.catalog') }}" class="inline-block mt-6 px-6 py-3 rounded-full bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-4">
                @foreach($cart as $item)
                    <div class="bg-white border border-stone-200 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-20 h-20 rounded-xl object-cover bg-stone-100 shrink-0">
                            <div>
                                <p class="text-xs text-zinc-500">{{ $item['seller_name'] }}</p>
                                <a href="{{ route('shop.product', $item['slug']) }}" class="font-bold text-zinc-900 hover:underline">{{ $item['name'] }}</a>
                                <p class="text-xs text-zinc-500 mt-1">Ukuran: <strong>{{ $item['size'] }}</strong> &bull; Warna: <strong>{{ $item['color'] }}</strong></p>
                                <p class="font-semibold text-sm text-zinc-900 mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 self-end sm:self-center">
                            <form action="{{ route('cart.update', $item['key']) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['max_stock'] }}"
                                       class="w-16 px-2.5 py-1.5 rounded-lg border border-stone-300 text-sm text-center">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-xs font-semibold text-zinc-700 cursor-pointer">
                                    Update
                                </button>
                            </form>

                            <form action="{{ route('cart.remove', $item['key']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <form action="{{ route('cart.clear') }}" method="POST" class="text-right">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-zinc-500 hover:text-rose-600 underline cursor-pointer">Kosongkan Keranjang</button>
                </form>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-white border border-stone-200 rounded-2xl p-6 sticky top-24 space-y-4">
                    <h2 class="font-bold text-lg text-zinc-900">Ringkasan Belanja</h2>
                    <div class="space-y-2 text-sm border-b border-stone-100 pb-4">
                        <div class="flex justify-between text-zinc-600">
                            <span>Subtotal</span>
                            <span class="font-semibold text-zinc-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-600">
                            <span>Estimasi Ongkir</span>
                            <span class="font-semibold text-zinc-900">
                                {{ $subtotal >= 300000 ? 'Gratis' : 'Rp 15.000' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-between items-baseline">
                        <span class="font-bold text-zinc-900">Total</span>
                        <span class="text-xl font-bold text-zinc-900">
                            Rp {{ number_format($subtotal + ($subtotal >= 300000 ? 0 : 15000), 0, ',', '.') }}
                        </span>
                    </div>
                    <a href="{{ route('checkout') }}" class="block w-full text-center px-6 py-3.5 rounded-full bg-zinc-900 text-white font-semibold text-sm hover:bg-zinc-800 transition">
                        Lanjut ke Checkout
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

