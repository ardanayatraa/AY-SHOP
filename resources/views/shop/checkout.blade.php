@extends('layouts.app')

@section('title', 'Checkout — AY SHOP')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 mb-8">Checkout Pesanan</h1>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        @csrf

        <div class="lg:col-span-7 bg-white border border-stone-200 rounded-2xl p-6 space-y-5">
            <h2 class="font-bold text-lg text-zinc-900 border-b border-stone-100 pb-3">Informasi Pengiriman</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('customer_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Email</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('customer_email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">No. WhatsApp / HP</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()?->phone) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('customer_phone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kota / Kabupaten</label>
                    <input type="text" name="city" value="{{ old('city') }}" required placeholder="Contoh: Bandung"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('city') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="40115"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Alamat Lengkap</label>
                <textarea name="shipping_address" rows="3" required placeholder="Nama jalan, nomor rumah, RT/RW, kecamatan..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">{{ old('shipping_address', auth()->user()?->address) }}</textarea>
                @error('shipping_address') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach(['transfer' => 'Transfer Bank', 'ewallet' => 'E-Wallet (QRIS)', 'cod' => 'Bayar di Tempat (COD)'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $val }}" class="peer sr-only" @checked(old('payment_method', 'transfer') === $val)>
                            <div class="p-3.5 rounded-xl border border-stone-300 text-sm font-medium text-center peer-checked:bg-zinc-900 peer-checked:text-white peer-checked:border-zinc-900 transition">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Catatan untuk Seller (Opsional)</label>
                <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Misal: Tolong bungkus rapi"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="bg-white border border-stone-200 rounded-2xl p-6 space-y-4 sticky top-24">
                <h2 class="font-bold text-lg text-zinc-900 border-b border-stone-100 pb-3">Ringkasan Pesanan</h2>
                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @foreach($cart as $item)
                        <div class="flex justify-between gap-3 text-sm">
                            <div>
                                <p class="font-semibold text-zinc-900">{{ $item['name'] }}</p>
                                <p class="text-xs text-zinc-500">{{ $item['seller_name'] }} &bull; {{ $item['size'] }}, {{ $item['color'] }} &times; {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-semibold text-zinc-900 shrink-0">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-stone-100 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-zinc-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-zinc-600">
                        <span>Ongkos Kirim</span>
                        <span>{{ $shippingCost === 0 ? 'Gratis' : 'Rp ' . number_format($shippingCost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-zinc-900 pt-2 border-t border-stone-100">
                        <span>Total Bayar</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" class="w-full px-6 py-3.5 rounded-full bg-zinc-900 text-white font-semibold text-sm hover:bg-zinc-800 transition cursor-pointer">
                    Buat Pesanan Sekarang
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

