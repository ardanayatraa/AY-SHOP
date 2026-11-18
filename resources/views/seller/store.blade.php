@extends('layouts.seller')

@section('title', 'Pengaturan Toko — Menu Seller AY SHOP')
@section('header', 'Pengaturan Toko')

@section('content')
    <div class="max-w-2xl bg-white border border-stone-200 rounded-2xl p-6 sm:p-8">
        <form action="{{ route('seller.store.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Nama Toko</label>
                <input type="text" name="store_name" value="{{ old('store_name', $seller->store_name) }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                @error('store_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kota Asal</label>
                    <input type="text" name="city" value="{{ old('city', $seller->city) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('city') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">No. WhatsApp / Telepon Toko</label>
                    <input type="text" name="phone" value="{{ old('phone', $seller->phone) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('phone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Deskripsi Toko</label>
                <textarea name="description" rows="4"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">{{ old('description', $seller->description) }}</textarea>
            </div>

            <button type="submit" class="px-6 py-3 rounded-full bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition cursor-pointer">
                Simpan Pengaturan Toko
            </button>
        </form>
    </div>
@endsection

