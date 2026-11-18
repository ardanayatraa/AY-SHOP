<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        return view('shop.cart', compact('cart', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        if (! $product->is_active || $product->stock < 1) {
            return back()->with('error', 'Produk sedang tidak tersedia atau stok habis.');
        }

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:' . $product->stock],
            'size' => ['nullable', 'string', 'max:30'],
            'color' => ['nullable', 'string', 'max:50'],
        ]);

        $qty = $validated['quantity'] ?? 1;
        $size = $validated['size'] ?? ($product->sizes[0] ?? 'All Size');
        $color = $validated['color'] ?? ($product->colors[0] ?? 'Default');

        $cart = session('cart', []);
        $key = $product->id . '-' . Str::slug($size) . '-' . Str::slug($color);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = min($product->stock, $cart[$key]['quantity'] + $qty);
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'seller_id' => $product->seller_id,
                'seller_name' => $product->seller->store_name,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->image,
                'price' => $product->effective_price,
                'size' => $size,
                'color' => $color,
                'quantity' => $qty,
                'max_stock' => $product->stock,
            ];
        }

        session(['cart' => $cart]);

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout');
        }

        return back()->with('success', "{$product->name} ({$size}, {$color}) ditambahkan ke keranjang!");
    }

    public function update(Request $request, string $key)
    {
        $cart = session('cart', []);
        if (! isset($cart[$key])) {
            return back();
        }

        $qty = max(1, (int) $request->input('quantity', 1));
        $cart[$key]['quantity'] = min($cart[$key]['max_stock'], $qty);
        session(['cart' => $cart]);

        return back()->with('success', 'Jumlah produk di keranjang diperbarui.');
    }

    public function remove(string $key)
    {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Keranjang belanja dikosongkan.');
    }

    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.catalog')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $shippingCost = $subtotal >= 300000 ? 0 : 15000;
        $total = $subtotal + $shippingCost;

        return view('shop.checkout', compact('cart', 'subtotal', 'shippingCost', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.catalog')->with('error', 'Keranjang belanja kosong.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:15'],
            'payment_method' => ['required', 'in:transfer,cod,ewallet'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order = DB::transaction(function () use ($cart, $validated, $request) {
            $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
            $shippingCost = $subtotal >= 300000 ? 0 : 15000;

            $order = Order::create([
                'order_number' => 'AY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => $request->user()?->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'] ?? null,
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $subtotal + $shippingCost,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product?->id,
                    'seller_id' => $item['seller_id'],
                    'product_name' => $item['name'],
                    'size' => $item['size'],
                    'color' => $item['color'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'status' => 'pending',
                ]);

                if ($product) {
                    $product->decrement('stock', min($product->stock, $item['quantity']));
                    $product->increment('sold_count', $item['quantity']);
                }
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function success(string $orderNumber)
    {
        $order = Order::with(['items.seller'])->where('order_number', $orderNumber)->firstOrFail();

        return view('shop.checkout-success', compact('order'));
    }

    public function myOrders(Request $request)
    {
        $orders = Order::with(['items.seller'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('shop.my-orders', compact('orders'));
    }
}

