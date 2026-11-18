@extends('layouts.app')

@section('title', 'Daftar Akun — AY SHOP')

@section('content')
<div class="max-w-lg mx-auto px-4 py-12">
    <div class="bg-white border border-stone-200 rounded-3xl p-8 shadow-sm">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-zinc-900">Daftar di AY SHOP</h1>
            <p class="text-sm text-zinc-500 mt-1">Pilih daftar sebagai Pembeli atau langsung buka Toko Seller</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-2">Jenis Akun</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="account_type" value="customer" class="peer sr-only" @checked(old('account_type', 'customer') === 'customer') onchange="toggleSellerFields(false)">
                        <div class="p-3 rounded-xl border border-stone-300 text-center text-sm font-semibold peer-checked:bg-zinc-900 peer-checked:text-white peer-checked:border-zinc-900 transition">
                            Pembeli
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="account_type" value="seller" class="peer sr-only" @checked(old('account_type') === 'seller') onchange="toggleSellerFields(true)">
                        <div class="p-3 rounded-xl border border-stone-300 text-center text-sm font-semibold peer-checked:bg-amber-400 peer-checked:text-zinc-900 peer-checked:border-amber-400 transition">
                            Seller (Buka Toko)
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">No. WhatsApp / HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div id="seller-fields" class="space-y-4 p-4 rounded-2xl bg-amber-50/60 border border-amber-200 {{ old('account_type') === 'seller' ? '' : 'hidden' }}">
                <p class="text-xs font-bold text-amber-900 uppercase tracking-wider">Informasi Toko Seller</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Nama Toko</label>
                        <input type="text" name="store_name" value="{{ old('store_name') }}" placeholder="Contoh: Studio Mode ID"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 bg-white text-sm focus:outline-none focus:border-zinc-900">
                        @error('store_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kota Asal Toko</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="Contoh: Bandung"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 bg-white text-sm focus:outline-none focus:border-zinc-900">
                        @error('city') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" required class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Konfirmasi Sandi</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-full bg-zinc-900 text-white font-semibold text-sm hover:bg-zinc-800 transition cursor-pointer">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-xs text-zinc-500 mt-5">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-zinc-900 hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>

<script>
function toggleSellerFields(show) {
    const el = document.getElementById('seller-fields');
    if (show) {
        el.classList.remove('hidden');
    } else {
        el.classList.add('hidden');
    }
}
</script>
@endsection

