<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user()->seller;

        $totalProducts = $seller->products()->count();
        $activeProducts = $seller->products()->where('is_active', true)->count();
        $totalSold = (int) $seller->products()->sum('sold_count');
        $totalRevenue = (int) $seller->orderItems()
            ->where('status', '!=', 'cancelled')
            ->sum('subtotal');
        $pendingOrdersCount = $seller->orderItems()
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        $recentOrderItems = $seller->orderItems()
            ->with(['order', 'product'])
            ->latest()
            ->take(8)
            ->get();

        $topProducts = $seller->products()
            ->orderByDesc('sold_count')
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'seller',
            'totalProducts',
            'activeProducts',
            'totalSold',
            'totalRevenue',
            'pendingOrdersCount',
            'recentOrderItems',
            'topProducts'
        ));
    }

    public function showRegister(Request $request)
    {
        if ($request->user()->seller) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.register');
    }

    public function registerStore(Request $request)
    {
        $user = $request->user();
        if ($user->seller) {
            return redirect()->route('seller.dashboard');
        }

        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($user, $validated) {
            $baseSlug = Str::slug($validated['store_name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Seller::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            Seller::create([
                'user_id' => $user->id,
                'store_name' => $validated['store_name'],
                'slug' => $slug,
                'city' => $validated['city'],
                'phone' => $validated['phone'],
                'description' => $validated['description'] ?? 'Toko pakaian terpercaya di AY SHOP.',
                'is_verified' => false,
                'is_active' => true,
            ]);

            $user->update(['role' => 'seller']);
        });

        return redirect()->route('seller.dashboard')->with('success', 'Selamat! Toko Anda berhasil dibuka.');
    }

    public function orders(Request $request)
    {
        $seller = $request->user()->seller;

        $query = $seller->orderItems()->with(['order', 'product'])->latest();

        if ($status = $request->string('status')->value()) {
            $query->where('status', $status);
        }

        $orderItems = $query->paginate(15)->withQueryString();

        return view('seller.orders', compact('seller', 'orderItems'));
    }

    public function updateOrderStatus(Request $request, OrderItem $orderItem)
    {
        $seller = $request->user()->seller;

        if ($orderItem->seller_id !== $seller->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,completed,cancelled'],
        ]);

        $orderItem->update(['status' => $validated['status']]);

        // Sync parent order status if all items share a terminal or progressed state
        $order = $orderItem->order;
        $statuses = $order->items()->pluck('status')->unique();
        if ($statuses->count() === 1) {
            $order->update(['status' => $statuses->first()]);
        } elseif ($statuses->contains('processing') || $statuses->contains('shipped')) {
            $order->update(['status' => 'processing']);
        }

        return back()->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diperbarui.');
    }

    public function editStore(Request $request)
    {
        $seller = $request->user()->seller;

        return view('seller.store', compact('seller'));
    }

    public function updateStore(Request $request)
    {
        $seller = $request->user()->seller;

        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $seller->update($validated);

        return back()->with('success', 'Profil toko berhasil diperbarui.');
    }
}

