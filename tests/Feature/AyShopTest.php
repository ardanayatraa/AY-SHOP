<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AyShopTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_populate_sellers_categories_products_and_orders(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('categories', 6);
        $this->assertDatabaseCount('sellers', 4);
        $this->assertDatabaseCount('products', 20);
        $this->assertDatabaseCount('orders', 3);
    }

    public function test_storefront_pages_render_with_seeded_content(): void
    {
        $this->seed(DatabaseSeeder::class);

        $product = Product::first();
        $seller = Seller::first();

        $this->get(route('home'))->assertStatus(200)->assertSee('AY SHOP');
        $this->get(route('shop.catalog'))->assertStatus(200)->assertSee($product->name);
        $this->get(route('shop.product', $product->slug))->assertStatus(200)->assertSee($product->name);
        $this->get(route('shop.sellers'))->assertStatus(200)->assertSee($seller->store_name);
        $this->get(route('shop.seller', $seller->slug))->assertStatus(200)->assertSee($seller->store_name);
    }

    public function test_customer_can_add_to_cart_and_checkout(): void
    {
        $this->seed(DatabaseSeeder::class);

        $product = Product::where('stock', '>=', 5)->first();
        $initialStock = $product->stock;

        $this->post(route('cart.add', $product), [
            'quantity' => 2,
            'size' => $product->sizes[0] ?? 'M',
            'color' => $product->colors[0] ?? 'Hitam',
        ])->assertRedirect();

        $this->get(route('cart.index'))->assertStatus(200)->assertSee($product->name);

        $response = $this->post(route('checkout.process'), [
            'customer_name' => 'Rina Wijaya',
            'customer_email' => 'rina@example.com',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Merdeka No. 12',
            'city' => 'Bandung',
            'postal_code' => '40111',
            'payment_method' => 'transfer',
        ]);

        $order = Order::where('customer_email', 'rina@example.com')->first();
        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order->order_number));

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertEquals($initialStock - 2, $product->fresh()->stock);
    }

    public function test_seller_can_access_seller_menu_and_manage_own_products(): void
    {
        $this->seed(DatabaseSeeder::class);

        $sellerUser = User::where('email', 'seller1@ayshop.id')->first();
        $otherSellerProduct = Product::where('seller_id', '!=', $sellerUser->seller->id)->first();
        $category = Category::first();

        $this->actingAs($sellerUser);

        $this->get(route('seller.dashboard'))->assertStatus(200)->assertSee($sellerUser->seller->store_name);
        $this->get(route('seller.products.index'))->assertStatus(200);
        $this->get(route('seller.orders'))->assertStatus(200);
        $this->get(route('seller.store'))->assertStatus(200);

        // Create new product
        $this->post(route('seller.products.store'), [
            'name' => 'Jaket Varsity AY Test',
            'category_id' => $category->id,
            'price' => 350000,
            'discount_price' => 299000,
            'stock' => 15,
            'sizes' => 'M, L, XL',
            'colors' => 'Hitam, Cream',
            'material' => 'Fleece & Kulit Sintetis',
            'description' => 'Jaket varsity keren untuk test.',
            'is_active' => '1',
        ])->assertRedirect(route('seller.products.index'));

        $this->assertDatabaseHas('products', [
            'seller_id' => $sellerUser->seller->id,
            'name' => 'Jaket Varsity AY Test',
        ]);

        // Cannot edit another seller's product
        $this->get(route('seller.products.edit', $otherSellerProduct))->assertStatus(403);
    }
}

