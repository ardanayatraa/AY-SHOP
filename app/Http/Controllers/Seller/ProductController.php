<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user()->seller;

        $query = $seller->products()->with('category')->latest();

        if ($search = $request->string('q')->trim()->value()) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($categoryId = $request->integer('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('seller.products.index', compact('seller', 'products', 'categories'));
    }

    public function create(Request $request)
    {
        $seller = $request->user()->seller;
        $categories = Category::orderBy('name')->get();

        return view('seller.products.form', [
            'seller' => $seller,
            'categories' => $categories,
            'product' => new Product(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $seller = $request->user()->seller;
        $validated = $this->validateProduct($request);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = 'storage/' . $request->file('image')->store('products', 'public');
        }

        Product::create([
            'seller_id' => $seller->id,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?: null,
            'stock' => $validated['stock'],
            'sizes' => $this->parseCommaList($validated['sizes'] ?? 'S, M, L, XL'),
            'colors' => $this->parseCommaList($validated['colors'] ?? 'Hitam, Putih'),
            'material' => $validated['material'] ?? null,
            'image' => $imagePath,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk baru berhasil ditambahkan ke toko Anda!');
    }

    public function edit(Request $request, Product $product)
    {
        $seller = $request->user()->seller;
        if ($product->seller_id !== $seller->id) {
            abort(403);
        }

        $categories = Category::orderBy('name')->get();

        return view('seller.products.form', [
            'seller' => $seller,
            'categories' => $categories,
            'product' => $product,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $seller = $request->user()->seller;
        if ($product->seller_id !== $seller->id) {
            abort(403);
        }

        $validated = $this->validateProduct($request);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image && str_starts_with($product->image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
            }
            $imagePath = 'storage/' . $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?: null,
            'stock' => $validated['stock'],
            'sizes' => $this->parseCommaList($validated['sizes'] ?? 'S, M, L, XL'),
            'colors' => $this->parseCommaList($validated['colors'] ?? 'Hitam, Putih'),
            'material' => $validated['material'] ?? null,
            'image' => $imagePath,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil diperbarui!');
    }

    public function destroy(Request $request, Product $product)
    {
        $seller = $request->user()->seller;
        if ($product->seller_id !== $seller->id) {
            abort(403);
        }

        if ($product->image && str_starts_with($product->image, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleStatus(Request $request, Product $product)
    {
        $seller = $request->user()->seller;
        if ($product->seller_id !== $seller->id) {
            abort(403);
        }

        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', 'Status produk diperbarui.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:1000'],
            'discount_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'sizes' => ['nullable', 'string', 'max:200'],
            'colors' => ['nullable', 'string', 'max:200'],
            'material' => ['nullable', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function parseCommaList(string $raw): array
    {
        return collect(explode(',', $raw))
            ->map(fn ($v) => trim($v))
            ->filter()
            ->values()
            ->all();
    }
}

