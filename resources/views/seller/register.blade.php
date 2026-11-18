@extends('layouts.app')

@section('title', 'Buka Toko Seller — AY SHOP')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12">
    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <h1 class="text-2xl font-bold text-zinc-900">Buka Toko di AY SHOP</h1>
        <p class="text-sm text-zinc-500 mt-1 mb-6">Lengkapi profil toko Anda untuk mulai berjualan dan mengakses Menu Seller.</p>

        <form action="{{ route('seller.register.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Nama Toko</label>
                <input type="text" name="store_name" value="{{ old('store_name') }}" required placeholder="Contoh: Gaya Kita Apparel"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                @error('store_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kota</label>
                    <input type="text" name="city" value="{{ old('city') }}" required placeholder="Jakarta / Bandung / ..."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('city') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">No. Telepon Toko</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('phone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Deskripsi Singkat Toko</label>
                <textarea name="description" rows="3" placeholder="Ceritakan produk pakaian unggulan toko Anda..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="w-full py-3 rounded-full bg-zinc-900 text-white font-semibold text-sm hover:bg-zinc-800 transition cursor-pointer">
                Aktifkan Toko Sekarang
            </button>
        </form>
    </div>
</div>
@endsection

