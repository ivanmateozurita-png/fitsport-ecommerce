<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear categoria de prueba
        $this->category = Category::create([
            'name' => 'Ropa Deportiva',
            'slug' => 'ropa-deportiva',
        ]);

        // Crear productos de prueba
        Product::create([
            'name' => 'Camiseta Running',
            'description' => 'Camiseta para correr',
            'price' => 29.99,
            'stock' => 10,
            'category_id' => $this->category->id,
            'image_path' => 'img/test.jpg',
        ]);

        Product::create([
            'name' => 'Pantalon Gym',
            'description' => 'Pantalon para gimnasio',
            'price' => 39.99,
            'stock' => 5,
            'category_id' => $this->category->id,
            'image_path' => 'img/test2.jpg',
        ]);
    }

    public function test_catalog_page_is_accessible(): void
    {
        $response = $this->get(route('catalog.index'));

        $response->assertStatus(200);
        $response->assertViewIs('shop.catalog');
    }

    public function test_catalog_displays_products(): void
    {
        $response = $this->get(route('catalog.index'));

        $response->assertStatus(200);
        $response->assertSee('Camiseta Running');
    }

    public function test_catalog_search_finds_products(): void
    {
        $response = $this->get(route('catalog.index', ['q' => 'Running']));

        $response->assertStatus(200);
    }

    public function test_catalog_filter_by_category(): void
    {
        $response = $this->get(route('catalog.index', ['category_id' => $this->category->id]));

        $response->assertStatus(200);
    }
}
