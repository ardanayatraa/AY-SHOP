@extends('layouts.app')

@section('title', 'Pesanan Saya — AY SHOP')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 mb-8">Riwayat Pesanan Saya</h1>

    @if($orders->isEmpty())
        <div class="bg-white border border-stone-200 rounded-2xl p-12 text-center">
            <p class="text-zinc-600">Anda belum memiliki riwayat pesanan.</p>
            <a href="{{ route('shop.catalog') }}" class="inline-block mt-4 px-6 py-2.5 rounded-full bg-zinc-900 text-white text-sm font-semibold">Mulai Belanja</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white border border-stone-200 rounded-2xl p-5 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-stone-100 pb-3">
                        <div>
                            <span class="font-mono font-bold text-sm text-zinc-900">{{ $order->order_number }}</span>
                            <span class="text-xs text-zinc-500 ml-2">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                            {{ \App\Models\Order::statusLabel($order->status) }}
                        </span>
                    </div>

                    <div class="space-y-2 text-sm">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-zinc-900">{{ $item->product_name }}</p>
                                    <p class="text-xs text-zinc-500">{{ $item->seller->store_name }} &bull; {{ $item->size }}, {{ $item->color }} &times; {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-zinc-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    <span class="text-[11px] text-zinc-500">{{ \App\Models\Order::statusLabel($item->status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-stone-100 pt-3 flex justify-between items-center text-sm">
                        <span class="text-zinc-500">Metode: <strong class="uppercase text-zinc-800">{{ $order->payment_method }}</strong></span>
                        <span class="font-bold text-zinc-900">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection

