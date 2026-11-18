<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Menu Seller — AY SHOP')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-zinc-900 antialiased min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-zinc-900 text-stone-200 hidden md:flex flex-col shrink-0">
        <div class="p-6 border-b border-zinc-800">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-400 text-zinc-900 flex items-center justify-center font-bold text-xs">AY</div>
                <div>
                    <span class="font-bold text-white tracking-tight">AY SHOP</span>
                    <span class="block text-[10px] uppercase tracking-widest text-amber-400 font-semibold">Menu Seller</span>
                </div>
            </a>
        </div>

        @php $currentSeller = auth()->user()->seller; @endphp
        <div class="px-5 py-4 border-b border-zinc-800 bg-zinc-950/40">
            <p class="text-xs text-stone-400">Toko Aktif</p>
            <p class="font-semibold text-white truncate mt-0.5">{{ $currentSeller->store_name }}</p>
            <div class="flex items-center gap-2 mt-1 text-xs text-stone-400">
                <span>{{ $currentSeller->city }}</span>
                @if($currentSeller->is_verified)
                    <span class="px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-medium">Verified</span>
                @endif
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 text-sm">
            <a href="{{ route('seller.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('seller.dashboard') ? 'bg-amber-400 text-zinc-900 font-semibold' : 'text-stone-300 hover:bg-zinc-800 hover:text-white' }}">
                <span>Dashboard Toko</span>
            </a>
            <a href="{{ route('seller.products.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('seller.products.*') ? 'bg-amber-400 text-zinc-900 font-semibold' : 'text-stone-300 hover:bg-zinc-800 hover:text-white' }}">
                <span>Produk Saya</span>
            </a>
            <a href="{{ route('seller.products.create') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition text-stone-300 hover:bg-zinc-800 hover:text-white">
                <span>+ Tambah Produk</span>
            </a>
            <a href="{{ route('seller.orders') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('seller.orders*') ? 'bg-amber-400 text-zinc-900 font-semibold' : 'text-stone-300 hover:bg-zinc-800 hover:text-white' }}">
                <span>Pesanan Masuk</span>
            </a>
            <a href="{{ route('seller.store') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('seller.store*') ? 'bg-amber-400 text-zinc-900 font-semibold' : 'text-stone-300 hover:bg-zinc-800 hover:text-white' }}">
                <span>Pengaturan Toko</span>
            </a>
            <a href="{{ route('mcp-hub.chat.show') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition text-stone-300 hover:bg-zinc-800 hover:text-white">
                <span>Chat AI (MCP)</span>
            </a>
            <a href="{{ route('mcp-hub.settings.show') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition text-stone-300 hover:bg-zinc-800 hover:text-white">
                <span>Pengaturan MCP Hub</span>
            </a>
        </nav>

        <div class="p-4 border-t border-zinc-800 space-y-2 text-sm">
            <a href="{{ route('shop.seller', $currentSeller->slug) }}" class="block text-center px-3 py-2 rounded-xl bg-zinc-800 text-stone-200 hover:bg-zinc-700 transition text-xs font-medium">
                Lihat Halaman Toko Publik
            </a>
            <a href="{{ route('home') }}" class="block text-center px-3 py-2 rounded-xl border border-zinc-700 text-stone-300 hover:text-white transition text-xs">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </aside>

    <!-- Main Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Bar -->
        <header class="bg-white border-b border-stone-200 h-16 px-4 sm:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="md:hidden font-bold text-zinc-900">AY SHOP</a>
                <h1 class="font-semibold text-lg text-zinc-900">@yield('header', 'Seller Center')</h1>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <div class="md:hidden flex items-center gap-2">
                    <a href="{{ route('seller.dashboard') }}" class="px-2.5 py-1 rounded-lg bg-stone-100 text-xs font-medium">Dashboard</a>
                    <a href="{{ route('seller.products.index') }}" class="px-2.5 py-1 rounded-lg bg-stone-100 text-xs font-medium">Produk</a>
                    <a href="{{ route('seller.orders') }}" class="px-2.5 py-1 rounded-lg bg-stone-100 text-xs font-medium">Pesanan</a>
                </div>
                <span class="hidden sm:inline text-zinc-500">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-stone-200 text-xs font-medium text-zinc-600 hover:bg-stone-100">
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-8 max-w-6xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <x-hub-chat />
</body>
</html>

