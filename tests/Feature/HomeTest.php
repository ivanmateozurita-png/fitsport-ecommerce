<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('shop.home');
    }

    public function test_home_page_shows_products(): void
    {
        // Crear categoria y producto
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        Product::create([
            'name' => 'Producto Destacado',
            'description' => 'Descripcion del producto',
            'price' => 49.99,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 'img/test.jpg',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Producto Destacado');
    }

    public function test_home_works_with_empty_database(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }
}
