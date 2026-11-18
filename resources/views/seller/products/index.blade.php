@extends('layouts.seller')

@section('title', 'Produk Saya — Menu Seller AY SHOP')
@section('header', 'Produk Saya')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <form action="{{ route('seller.products.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk..."
                   class="px-3.5 py-2 rounded-xl bg-white border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
            <select name="category_id" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl bg-white border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 rounded-xl bg-stone-200 text-zinc-800 text-sm font-medium hover:bg-stone-300 cursor-pointer">Cari</button>
        </form>

        <a href="{{ route('seller.products.create') }}" class="px-5 py-2.5 rounded-full bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition self-start">
            + Tambah Produk Baru
        </a>
    </div>

    <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-50 border-b border-stone-200 text-xs uppercase text-zinc-500">
                    <tr>
                        <th class="px-5 py-3.5">Produk</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Harga</th>
                        <th class="px-5 py-3.5">Stok</th>
                        <th class="px-5 py-3.5">Terjual</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-stone-50/60">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-11 h-11 rounded-xl object-cover bg-stone-100 shrink-0">
                                    @endif
                                    <div>
                                        <p class="font-semibold text-zinc-900">{{ $product->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ implode(', ', $product->sizes ?? []) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-zinc-600">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-zinc-900">Rp {{ number_format($product->effective_price, 0, ',', '.') }}</p>
                                @if($product->discount_percent > 0)
                                    <p class="text-xs text-zinc-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-medium {{ $product->stock < 5 ? 'text-rose-600' : 'text-zinc-800' }}">{{ $product->stock }}</td>
                            <td class="px-5 py-3.5 text-zinc-600">{{ $product->sold_count }}</td>
                            <td class="px-5 py-3.5">
                                <form action="{{ route('seller.products.toggle', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer {{ $product->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-zinc-500 border border-stone-200' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('seller.products.edit', $product) }}" class="text-xs font-semibold text-amber-700 hover:underline">Edit</a>
                                <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:underline cursor-pointer">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-zinc-500">
                                Belum ada produk di toko Anda. <a href="{{ route('seller.products.create') }}" class="text-amber-700 font-semibold underline">Tambah sekarang</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection

