<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Read-only JSON API for a signed-in user (used by the MCP Hub embedded chat).
 * Every list is scoped to the user who owns the token; nothing here changes data.
 */
class AccountApiController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);
        $key = 'api-login:'.strtolower($data['email']).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['message' => 'Too many attempts. Try again later.'], 429);
        }
        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            RateLimiter::hit($key, 300);

            return response()->json(['message' => 'These credentials do not match our records.'], 422);
        }
        RateLimiter::clear($key);
        $token = $user->createToken('mcp-hub-chat', ['read'], now()->addDays(30))->plainTextToken;

        return response()->json(['token' => $token, 'user' => ['name' => $user->name]]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'name' => $user->name,
            'role' => $user->role,
            'is_seller' => $user->isSeller(),
            'store_name' => $user->seller?->store_name,
        ]);
    }

    /** The user's own purchases. */
    public function myOrders(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->withCount('items')->latest()->limit(50)->get()
            ->map(fn (Order $order) => [
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total' => (int) $order->total,
                'items' => $order->items_count,
                'payment_method' => $order->payment_method,
                'ordered_at' => $order->created_at?->toIso8601String(),
            ]);

        return response()->json(['data' => $orders]);
    }

    /** The seller's own products. */
    public function sellerProducts(Request $request): JsonResponse
    {
        $seller = $request->user()->seller;
        abort_unless($seller, 403, 'Only sellers have products.');
        $query = Product::where('seller_id', $seller->id);
        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->string('q').'%');
        }
        $products = $query->latest()->limit(50)->get()->map(fn (Product $product) => [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (int) $product->price,
            'discount_price' => $product->discount_price !== null ? (int) $product->discount_price : null,
            'stock' => (int) $product->stock,
            'sold' => (int) $product->sold_count,
            'active' => (bool) $product->is_active,
        ]);

        return response()->json(['data' => $products]);
    }

    /** Order lines sold by the seller. */
    public function sellerOrders(Request $request): JsonResponse
    {
        $seller = $request->user()->seller;
        abort_unless($seller, 403, 'Only sellers have store orders.');
        $query = OrderItem::where('seller_id', $seller->id)->with('order:id,order_number,created_at');
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        $items = $query->latest()->limit(50)->get()->map(fn (OrderItem $item) => [
            'order_number' => $item->order?->order_number,
            'product' => $item->product_name,
            'quantity' => (int) $item->quantity,
            'subtotal' => (int) $item->subtotal,
            'status' => $item->status,
            'ordered_at' => $item->order?->created_at?->toIso8601String(),
        ]);

        return response()->json(['data' => $items]);
    }

    /** The public catalogue, for questions about what the shop sells. */
    public function searchProducts(Request $request): JsonResponse
    {
        $query = Product::where('is_active', true)->with('seller:id,store_name');
        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->string('q').'%');
        }
        $products = $query->orderByDesc('sold_count')->limit(20)->get()->map(fn (Product $product) => [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (int) ($product->discount_price ?? $product->price),
            'stock' => (int) $product->stock,
            'store' => $product->seller?->store_name,
        ]);

        return response()->json(['data' => $products]);
    }
}
