<?php

namespace Tests\Feature;

use App\Mcp\ActorResolver;
use App\Mcp\Policies\OrderItemPolicy;
use App\Mcp\Policies\ProductPolicy;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class McpHubIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_mcp_hub_health_and_capabilities_endpoints_work_with_app_token(): void
    {
        config([
            'mcp-hub.enabled' => true,
            'mcp-hub.app_key' => 'ay_shop',
            'mcp-hub.app_token' => 'test_app_token_with_at_least_32_characters',
            'mcp-hub.delegation_secret' => 'test_delegation_secret_at_least_32_chars',
        ]);

        $headers = [
            'Authorization' => 'Bearer test_app_token_with_at_least_32_characters',
        ];

        $this->getJson('/api/ai/v1/health', $headers)
            ->assertStatus(200);

        $this->getJson('/api/ai/v1/capabilities', $headers)
            ->assertStatus(200)
            ->assertSee('products')
            ->assertSee('categories')
            ->assertSee('sellers')
            ->assertSee('orders')
            ->assertSee('order_items');
    }

    public function test_actor_resolver_and_policies_scope_seller_and_customer_records(): void
    {
        $this->seed(DatabaseSeeder::class);

        $resolver = app(ActorResolver::class);

        $sellerUser = User::with('seller')->where('email', 'seller1@ayshop.id')->firstOrFail();
        $sellerActor = $resolver->forUser($sellerUser);

        $this->assertSame('user:' . $sellerUser->id, $sellerActor->reference);
        $this->assertSame('seller:' . $sellerUser->seller->id, $sellerActor->tenant);

        $resolvedActor = $resolver->resolve($sellerActor->reference, $sellerActor->tenant);
        $this->assertSame($sellerUser->id, $resolvedActor->user->getAuthIdentifier());

        $productPolicy = new ProductPolicy();
        $this->assertTrue($productPolicy->allows($sellerActor, 'products', 'list'));

        $scopedProducts = $productPolicy->scope(Product::query(), $sellerActor)->get();
        $this->assertNotEmpty($scopedProducts);
        $this->assertTrue($scopedProducts->every(fn (Product $p) => $p->seller_id === $sellerUser->seller->id));

        $orderItemPolicy = new OrderItemPolicy();
        $scopedItems = $orderItemPolicy->scope(OrderItem::query(), $sellerActor)->get();
        $this->assertTrue($scopedItems->every(fn (OrderItem $item) => $item->seller_id === $sellerUser->seller->id));
    }

    public function test_seller_can_access_mcp_hub_web_ui(): void
    {
        $this->seed(DatabaseSeeder::class);

        $sellerUser = User::where('email', 'seller1@ayshop.id')->firstOrFail();
        $this->actingAs($sellerUser);

        $response = $this->get('/mcp-hub');
        $this->assertNotEquals(403, $response->getStatusCode());
    }
}

