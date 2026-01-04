<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_page_is_accessible(): void
    {
        $category = Category::create([
            'name' => 'Categoria Test',
            'slug' => 'categoria-test',
        ]);

        $product = Product::create([
            'name' => 'Producto Test',
            'description' => 'Descripcion del producto',
            'price' => 99.99,
            'stock' => 15,
            'category_id' => $category->id,
            'image_path' => 'img/test.jpg',
        ]);

        $response = $this->get(route('product.show', $product->id));

        $response->assertStatus(200);
        $response->assertViewIs('shop.product');
        $response->assertSee('Producto Test');
    }

    public function test_product_shows_price(): void
    {
        $category = Category::create([
            'name' => 'Categoria',
            'slug' => 'categoria',
        ]);

        $product = Product::create([
            'name' => 'Producto Precio',
            'description' => 'Descripcion',
            'price' => 59.99,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 'img/test.jpg',
        ]);

        $response = $this->get(route('product.show', $product->id));

        $response->assertStatus(200);
        $response->assertSee('59.99');
    }

    public function test_product_not_found_returns_404(): void
    {
        $response = $this->get(route('product.show', 999));

        $response->assertStatus(404);
    }
}
