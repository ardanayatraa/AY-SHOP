@extends('layouts.app')

@section('title', 'Pesanan Berhasil — AY SHOP')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-white border border-stone-200 rounded-3xl p-8 text-center space-y-4">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto text-2xl font-bold">
            ✓
        </div>
        <h1 class="text-2xl font-bold text-zinc-900">Terima Kasih! Pesanan Anda Diterima</h1>
        <p class="text-sm text-zinc-600">
            Nomor Pesanan: <span class="font-mono font-bold text-zinc-900 bg-stone-100 px-2.5 py-1 rounded-lg">{{ $order->order_number }}</span>
        </p>

        <div class="text-left bg-stone-50 border border-stone-200 rounded-2xl p-5 mt-6 space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-zinc-500">Penerima</span>
                <span class="font-semibold text-zinc-900">{{ $order->customer_name }} ({{ $order->customer_phone }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500">Alamat</span>
                <span class="font-medium text-zinc-800 text-right max-w-sm">{{ $order->shipping_address }}, {{ $order->city }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-zinc-500">Metode Pembayaran</span>
                <span class="font-semibold uppercase text-zinc-900">{{ $order->payment_method }}</span>
            </div>
            <div class="border-t border-stone-200 pt-3 space-y-2">
                @foreach($order->items as $item)
                    <div class="flex justify-between">
                        <span>{{ $item->product_name }} ({{ $item->size }}, {{ $item->color }}) &times; {{ $item->quantity }} <em class="text-xs text-zinc-500">— {{ $item->seller->store_name }}</em></span>
                        <span class="font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-stone-200 pt-3 flex justify-between font-bold text-base text-zinc-900">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="pt-4 flex flex-wrap justify-center gap-3">
            <a href="{{ route('shop.catalog') }}" class="px-6 py-3 rounded-full bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition">
                Belanja Lagi
            </a>
            @auth
                <a href="{{ route('orders.my') }}" class="px-6 py-3 rounded-full border border-stone-300 text-zinc-700 text-sm font-semibold hover:bg-stone-100 transition">
                    Lihat Pesanan Saya
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection

