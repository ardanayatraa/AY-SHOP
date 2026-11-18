<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products' => fn ($q) => $q->where('is_active', true)])->get();

        $featuredProducts = Product::with(['seller', 'category'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::with(['seller', 'category'])
            ->where('is_active', true)
            ->latest()
            ->take(12)
            ->get();

        $sellers = Seller::where('is_active', true)
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->orderByDesc('rating')
            ->take(4)
            ->get();

        return view('shop.home', compact('categories', 'featuredProducts', 'latestProducts', 'sellers'));
    }

    public function shop(Request $request)
    {
        $categories = Category::withCount(['products' => fn ($q) => $q->where('is_active', true)])->get();
        $sellers = Seller::where('is_active', true)->orderBy('store_name')->get();

        $query = Product::with(['seller', 'category'])->where('is_active', true);

        if ($search = $request->string('q')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('material', 'like', "%{$search}%");
            });
        }

        if ($categorySlug = $request->string('category')->value()) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($sellerSlug = $request->string('seller')->value()) {
            $query->whereHas('seller', fn ($q) => $q->where('slug', $sellerSlug));
        }

        match ($request->string('sort')->value()) {
            'cheapest' => $query->orderByRaw('COALESCE(discount_price, price) ASC'),
            'expensive' => $query->orderByRaw('COALESCE(discount_price, price) DESC'),
            'bestseller' => $query->orderByDesc('sold_count'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('shop.catalog', compact('products', 'categories', 'sellers'));
    }

    public function product(string $slug)
    {
        $product = Product::with(['seller', 'category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['seller', 'category'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                    ->orWhere('seller_id', $product->seller_id);
            })
            ->take(4)
            ->get();

        return view('shop.product', compact('product', 'relatedProducts'));
    }

    public function sellers()
    {
        $sellers = Seller::where('is_active', true)
            ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->orderByDesc('is_verified')
            ->orderByDesc('rating')
            ->get();

        return view('shop.sellers', compact('sellers'));
    }

    public function seller(string $slug)
    {
        $seller = Seller::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $seller->products()
            ->with('category')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('shop.seller-store', compact('seller', 'products'));
    }
}

