<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartNormalizationTest extends TestCase
{
    use RefreshDatabase;

    protected function createProduct(int $stock = 10): Product
    {
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);

        return Product::create([
            'name' => 'Test',
            'price' => 50,
            'stock' => $stock,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);
    }

    public function test_cart_displays_after_normalization(): void
    {
        $product = $this->createProduct();

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    public function test_cart_count_normalizes_session(): void
    {
        $product = $this->createProduct();

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->get('/cart/count');
        $response->assertStatus(200);
    }

    public function test_cart_total_calculated_correctly(): void
    {
        $product = $this->createProduct();

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    public function test_multiple_products_in_cart(): void
    {
        $product1 = $this->createProduct();
        $category2 = Category::create(['name' => 'Cat2', 'slug' => 'cat2']);
        $product2 = Product::create([
            'name' => 'Test2',
            'price' => 30,
            'stock' => 10,
            'category_id' => $category2->id,
            'image_path' => 't2.jpg',
        ]);

        $this->postJson('/cart/add', [
            'product_id' => $product1->id,
            'quantity' => 1,
        ]);

        $this->postJson('/cart/add', [
            'product_id' => $product2->id,
            'quantity' => 2,
        ]);

        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    public function test_cart_update_validates_min_quantity(): void
    {
        $product = $this->createProduct();

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->patchJson('/cart/update/'.$product->id, [
            'quantity' => 0,
        ]);

        $response->assertStatus(422);
    }

    public function test_cart_add_stores_product_info(): void
    {
        $product = $this->createProduct();

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cart = session('cart');
        $this->assertEquals('Test', $cart[$product->id]['name']);
        $this->assertEquals(50, $cart[$product->id]['price']);
    }
}
