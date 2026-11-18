@extends('layouts.seller')

@section('title', ($isEdit ? 'Edit Produk' : 'Tambah Produk') . ' — Menu Seller AY SHOP')
@section('header', $isEdit ? 'Edit Produk' : 'Tambah Produk Baru')

@section('content')
    <div class="max-w-3xl bg-white border border-stone-200 rounded-2xl p-6 sm:p-8">
        <form action="{{ $isEdit ? route('seller.products.update', $product) : route('seller.products.store') }}"
              method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           placeholder="Contoh: Kemeja Linen Oversized"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Kategori</label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm bg-white focus:outline-none focus:border-zinc-900">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Harga Normal (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="1000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('price') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Harga Diskon (Opsional)</label>
                    <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" min="0"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('discount_price') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Stok (pcs)</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 10) }}" required min="0"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                    @error('stock') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Ukuran (pisahkan koma)</label>
                    <input type="text" name="sizes" value="{{ old('sizes', implode(', ', $product->sizes ?? ['S', 'M', 'L', 'XL'])) }}"
                           placeholder="S, M, L, XL"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Warna (pisahkan koma)</label>
                    <input type="text" name="colors" value="{{ old('colors', implode(', ', $product->colors ?? ['Hitam', 'Putih'])) }}"
                           placeholder="Hitam, Putih, Navy"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Bahan / Material</label>
                    <input type="text" name="material" value="{{ old('material', $product->material) }}"
                           placeholder="Cotton Combed 24s"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Deskripsi Produk</label>
                <textarea name="description" rows="4" required
                          class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-zinc-900">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1.5">Foto Produk (Opsional, Max 2MB)</label>
                @if($product->image)
                    <div class="mb-2">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 rounded-xl object-cover border border-stone-200">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-zinc-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-zinc-800 hover:file:bg-stone-200">
                @error('image') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-wrap gap-6 pt-2">
                <label class="flex items-center gap-2 text-sm text-zinc-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $isEdit ? $product->is_active : true)) class="rounded border-stone-300">
                    Tampilkan di Toko (Aktif)
                </label>
                <label class="flex items-center gap-2 text-sm text-zinc-700">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured)) class="rounded border-stone-300">
                    Jadikan Produk Unggulan
                </label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-stone-100">
                <button type="submit" class="px-6 py-3 rounded-full bg-zinc-900 text-white text-sm font-semibold hover:bg-zinc-800 transition cursor-pointer">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan Produk' }}
                </button>
                <a href="{{ route('seller.products.index') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-medium text-zinc-700 hover:bg-stone-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

