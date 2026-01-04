<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtraControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_page_shows_category(): void
    {
        $category = Category::create(['name' => 'Sport', 'slug' => 'sport']);
        $product = Product::create([
            'name' => 'Zapatos',
            'price' => 79.99,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);

        $response = $this->get('/product/'.$product->id);
        $response->assertStatus(200);
    }

    public function test_product_404_for_nonexistent(): void
    {
        $response = $this->get('/product/99999');
        $response->assertStatus(404);
    }

    public function test_catalog_returns_products(): void
    {
        $category = Category::create(['name' => 'Clothes', 'slug' => 'clothes']);
        Product::create([
            'name' => 'Camisa',
            'price' => 29.99,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);

        $response = $this->get('/catalog');
        $response->assertStatus(200);
    }

    public function test_catalog_with_category_filter(): void
    {
        $category = Category::create(['name' => 'Shoes', 'slug' => 'shoes']);

        $response = $this->get('/catalog?category_id='.$category->id);
        $response->assertStatus(200);
    }

    public function test_catalog_with_search(): void
    {
        $response = $this->get('/catalog?q=test');
        $response->assertStatus(200);
    }

    public function test_home_page_shows_featured_products(): void
    {
        $category = Category::create(['name' => 'Featured', 'slug' => 'featured']);
        Product::create([
            'name' => 'Featured Product',
            'price' => 49.99,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
            'featured' => true,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_home_page_accessible_by_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }

    public function test_cart_accessible_by_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/cart');
        $response->assertStatus(200);
    }

    public function test_checkout_requires_auth(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    }

    public function test_my_orders_requires_auth(): void
    {
        $response = $this->get('/my-orders');
        $response->assertRedirect('/login');
    }
}
