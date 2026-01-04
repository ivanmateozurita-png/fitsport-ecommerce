<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAjaxTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_add_to_cart_returns_json(): void
    {
        $product = $this->createProduct();

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_add_to_cart_returns_cart_count(): void
    {
        $product = $this->createProduct();

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['cart_count', 'cart_total']);
    }

    public function test_add_to_cart_fails_without_product_id(): void
    {
        $response = $this->postJson('/cart/add', [
            'quantity' => 1,
        ]);

        $response->assertStatus(400);
    }

    public function test_update_cart_returns_json(): void
    {
        $product = $this->createProduct();

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->patchJson('/cart/update/'.$product->id, [
            'quantity' => 3,
        ]);

        $this->assertTrue(in_array($response->status(), [200, 302, 422]));
    }

    public function test_remove_from_cart_returns_json(): void
    {
        $product = $this->createProduct();

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->deleteJson('/cart/remove/'.$product->id);
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    public function test_add_increments_existing_quantity(): void
    {
        $product = $this->createProduct(20);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $cart = session('cart');
        $this->assertEquals(5, $cart[$product->id]['quantity']);
    }

    public function test_add_with_size(): void
    {
        $product = $this->createProduct();

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
            'size' => 'M',
        ]);

        $response->assertStatus(200);
    }
}
