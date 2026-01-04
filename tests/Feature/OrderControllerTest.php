<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createAuthenticatedUser(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    protected function createProduct(int $stock = 10): Product
    {
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        return Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => $stock,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);
    }

    public function test_checkout_shows_cart_items(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct();

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->get('/checkout');
        $response->assertStatus(200);
    }

    public function test_checkout_calculates_iva(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct();

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->get('/checkout');
        $response->assertStatus(200);
    }

    public function test_order_total_includes_iva(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct();

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Test',
            'email' => $user->email,
            'phone' => '123',
            'address' => 'Test Address',
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $expectedTotal = 99.99 * 1.15; // with IVA 15%
        $this->assertEqualsWithDelta($expectedTotal, $order->total, 0.02);
    }

    public function test_checkout_fails_insufficient_stock(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct(1);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Manually reduce stock to simulate race condition
        $product->update(['stock' => 0]);

        $response = $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Test',
            'email' => $user->email,
            'phone' => '123',
            'address' => 'Test Address',
        ]);

        $response->assertRedirect();
    }

    public function test_order_clears_cart_after_success(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct(10);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Test',
            'email' => $user->email,
            'phone' => '123',
            'address' => 'Test Address',
        ]);

        // Cart should be empty after successful order
        $this->assertNull(session('cart'));
    }
}
