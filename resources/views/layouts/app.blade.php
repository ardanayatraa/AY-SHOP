<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AY SHOP — Toko Pakaian Online Terpercaya')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 text-zinc-900 antialiased min-h-screen flex flex-col">
    <!-- Top Announcement Bar -->
    <div class="bg-zinc-900 text-stone-100 text-xs py-2 px-4 text-center tracking-wide">
        Gratis Ongkir seluruh Indonesia untuk pembelian minimal <span class="font-semibold text-amber-300">Rp 300.000</span> &bull; Koleksi Lokal Pilihan Terbaik
    </div>

    <!-- Main Navbar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                <!-- Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-zinc-900 text-white flex items-center justify-center font-bold text-sm tracking-tighter">
                        AY
                    </div>
                    <div class="leading-tight">
                        <span class="font-bold text-lg tracking-tight text-zinc-900">AY SHOP</span>
                        <span class="hidden sm:block text-[10px] uppercase tracking-widest text-zinc-500">Fashion Store</span>
                    </div>
                </a>

                <!-- Search Bar -->
                <form action="{{ route('shop.catalog') }}" method="GET" class="hidden md:flex flex-1 max-w-md">
                    <div class="relative w-full">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari kaos, kemeja linen, hoodie, dress..."
                            class="w-full pl-4 pr-16 py-2 text-sm bg-stone-100 border border-stone-200 rounded-full focus:outline-none focus:bg-white focus:border-zinc-900 transition"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-zinc-600 hover:text-zinc-900 cursor-pointer">
                            Cari
                        </button>
                    </div>
                </form>

                <!-- Navigation & Actions -->
                <nav class="flex items-center gap-2 sm:gap-4 text-sm font-medium">
                    <a href="{{ route('shop.catalog') }}" class="hidden sm:inline-block text-zinc-600 hover:text-zinc-900 px-2 py-1 transition {{ request()->routeIs('shop.catalog') ? 'text-zinc-900 font-semibold' : '' }}">
                        Katalog
                    </a>
                    <a href="{{ route('shop.sellers') }}" class="hidden sm:inline-block text-zinc-600 hover:text-zinc-900 px-2 py-1 transition {{ request()->routeIs('shop.sellers') ? 'text-zinc-900 font-semibold' : '' }}">
                        Toko Seller
                    </a>

                    <!-- Menu Seller -->
                    @auth
                        @if(auth()->user()->isSeller())
                            <a href="{{ route('seller.dashboard') }}" class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-amber-50 text-amber-900 border border-amber-200 hover:bg-amber-100 transition text-xs sm:text-sm font-semibold">
                                <span>Menu Seller</span>
                            </a>
                        @else
                            <a href="{{ route('seller.register') }}" class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-stone-100 text-zinc-800 border border-stone-200 hover:bg-stone-200 transition text-xs sm:text-sm font-semibold">
                                <span>Buka Toko</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1.5 rounded-full bg-amber-50 text-amber-900 border border-amber-200 hover:bg-amber-100 transition text-xs sm:text-sm font-semibold">
                            <span>Menu Seller</span>
                        </a>
                    @endauth

                    <!-- Cart -->
                    @php
                        $cartCount = collect(session('cart', []))->sum('quantity');
                    @endphp
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-stone-200 text-zinc-700 hover:text-zinc-900 hover:bg-stone-100 transition text-xs sm:text-sm font-medium">
                        <span>Keranjang</span>
                        @if($cartCount > 0)
                            <span class="min-w-[18px] h-[18px] px-1.5 rounded-full bg-zinc-900 text-white text-[10px] font-bold flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Auth -->
                    @auth
                        <div class="relative group">
                            <button type="button" class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-full hover:bg-stone-100 transition">
                                <span class="w-7 h-7 rounded-full bg-zinc-900 text-white text-xs font-bold flex items-center justify-center">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden lg:inline text-sm text-zinc-700 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-stone-200 py-1.5 z-50">
                                <div class="px-4 py-2 border-b border-stone-100">
                                    <p class="text-xs font-semibold text-zinc-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('orders.my') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-stone-50">Pesanan Saya</a>
                                @if(auth()->user()->isSeller())
                                    <a href="{{ route('seller.dashboard') }}" class="block px-4 py-2 text-sm text-amber-700 font-medium hover:bg-amber-50">Menu Seller (Dashboard)</a>
                                @else
                                    <a href="{{ route('seller.register') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-stone-50">Buka Toko Seller</a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST" class="border-t border-stone-100 mt-1 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-full bg-zinc-900 text-white text-xs sm:text-sm font-medium hover:bg-zinc-800 transition">
                            Masuk
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    @if(session('success') || session('error') || session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-xl bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="rounded-xl bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 text-sm">
                    {{ session('info') }}
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-zinc-900 text-stone-300 mt-16 border-t border-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-white text-zinc-900 flex items-center justify-center font-bold text-xs">AY</div>
                    <span class="font-bold text-lg text-white">AY SHOP</span>
                </div>
                <p class="text-sm text-stone-400 max-w-md leading-relaxed">
                    Platform toko pakaian online multi-seller yang menghadirkan koleksi fashion lokal berkualitas dari berbagai kota di Indonesia.
                </p>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Belanja</h4>
                <ul class="space-y-2 text-sm text-stone-400">
                    <li><a href="{{ route('shop.catalog') }}" class="hover:text-white transition">Semua Produk</a></li>
                    <li><a href="{{ route('shop.sellers') }}" class="hover:text-white transition">Daftar Toko Seller</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-white transition">Keranjang Belanja</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Seller Center</h4>
                <ul class="space-y-2 text-sm text-stone-400">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk sebagai Seller</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar Akun Toko</a></li>
                    <li><a href="{{ route('seller.dashboard') }}" class="hover:text-white transition">Menu Seller</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-zinc-800 py-5 text-center text-xs text-stone-500">
            &copy; {{ date('Y') }} AY SHOP. Dibuat dengan Laravel &amp; Tailwind CSS.
        </div>
    </footer>
    <x-hub-chat />
</body>
</html>

