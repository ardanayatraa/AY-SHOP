@extends('layouts.seller')

@section('title', 'Pesanan Masuk — Menu Seller AY SHOP')
@section('header', 'Pesanan Masuk')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-2 text-xs font-semibold">
            @foreach(['' => 'Semua', 'pending' => 'Menunggu', 'processing' => 'Dikemas', 'shipped' => 'Dikirim', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $key => $label)
                <a href="{{ route('seller.orders', $key ? ['status' => $key] : []) }}"
                   class="px-3.5 py-2 rounded-full border transition {{ request('status', '') === $key ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white text-zinc-700 border-stone-200 hover:bg-stone-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-50 border-b border-stone-200 text-xs uppercase text-zinc-500">
                    <tr>
                        <th class="px-5 py-3.5">No. Pesanan</th>
                        <th class="px-5 py-3.5">Pembeli</th>
                        <th class="px-5 py-3.5">Produk</th>
                        <th class="px-5 py-3.5">Subtotal</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Ubah Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($orderItems as $item)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-mono font-bold text-xs text-zinc-900">{{ $item->order->order_number }}</p>
                                <p class="text-[11px] text-zinc-500">{{ $item->created_at->format('d M Y, H:i') }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-zinc-900">{{ $item->order->customer_name }}</p>
                                <p class="text-xs text-zinc-500">{{ $item->order->city }} &bull; {{ $item->order->customer_phone }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-zinc-900">{{ $item->product_name }}</p>
                                <p class="text-xs text-zinc-500">{{ $item->size }}, {{ $item->color }} &times; {{ $item->quantity }}</p>
                            </td>
                            <td class="px-5 py-4 font-semibold text-zinc-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                    {{ \App\Models\Order::statusLabel($item->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <form action="{{ route('seller.orders.status', $item) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="px-2.5 py-1.5 rounded-lg border border-stone-300 text-xs bg-white">
                                        @foreach(['pending' => 'Menunggu', 'processing' => 'Dikemas', 'shipped' => 'Dikirim', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $st => $stLabel)
                                            <option value="{{ $st }}" @selected($item->status === $st)>{{ $stLabel }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-zinc-900 text-white text-xs font-semibold hover:bg-zinc-800 cursor-pointer">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-zinc-500">Belum ada pesanan masuk pada filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $orderItems->links() }}
    </div>
@endsection

