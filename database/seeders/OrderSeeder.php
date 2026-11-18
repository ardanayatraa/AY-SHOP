<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'budi@ayshop.id')->first();
        $products = Product::with('seller')->get();

        if ($products->count() < 4) {
            return;
        }

        $sampleOrders = [
            [
                'order_number' => 'AY-20261115-1001',
                'status' => 'completed',
                'payment_method' => 'transfer',
                'city' => 'Jakarta Pusat',
                'items' => [
                    ['product' => $products[0], 'size' => 'L', 'color' => 'Hitam', 'qty' => 2, 'status' => 'completed'],
                    ['product' => $products[5], 'size' => 'M', 'color' => 'Oatmeal', 'qty' => 1, 'status' => 'completed'],
                ],
            ],
            [
                'order_number' => 'AY-20261117-1002',
                'status' => 'processing',
                'payment_method' => 'ewallet',
                'city' => 'Bandung',
                'items' => [
                    ['product' => $products[2], 'size' => 'L', 'color' => 'Charcoal', 'qty' => 1, 'status' => 'processing'],
                    ['product' => $products[10], 'size' => 'M', 'color' => 'Dark Indigo', 'qty' => 1, 'status' => 'pending'],
                ],
            ],
            [
                'order_number' => 'AY-20261118-1003',
                'status' => 'pending',
                'payment_method' => 'cod',
                'city' => 'Surabaya',
                'items' => [
                    ['product' => $products[15], 'size' => 'M', 'color' => 'Sage', 'qty' => 1, 'status' => 'pending'],
                ],
            ],
        ];

        foreach ($sampleOrders as $orderData) {
            if (Order::where('order_number', $orderData['order_number'])->exists()) {
                continue;
            }

            $subtotal = 0;
            foreach ($orderData['items'] as $line) {
                $subtotal += $line['product']->effective_price * $line['qty'];
            }
            $shipping = 15000;

            $order = Order::create([
                'order_number' => $orderData['order_number'],
                'user_id' => $customer?->id,
                'customer_name' => $customer?->name ?? 'Budi Santoso',
                'customer_email' => $customer?->email ?? 'budi@ayshop.id',
                'customer_phone' => $customer?->phone ?? '081298765432',
                'shipping_address' => $customer?->address ?? 'Jl. Sudirman Kav. 10, Jakarta',
                'city' => $orderData['city'],
                'postal_code' => '10220',
                'payment_method' => $orderData['payment_method'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $subtotal + $shipping,
                'status' => $orderData['status'],
                'notes' => 'Tolong dibungkus rapi ya kak.',
            ]);

            foreach ($orderData['items'] as $line) {
                $p = $line['product'];
                $price = $p->effective_price;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'seller_id' => $p->seller_id,
                    'product_name' => $p->name,
                    'size' => $line['size'],
                    'color' => $line['color'],
                    'quantity' => $line['qty'],
                    'price' => $price,
                    'subtotal' => $price * $line['qty'],
                    'status' => $line['status'],
                ]);
            }
        }
    }
}

