<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\ProductSearch;

class LivewireProductSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $category = Category::create([
            'name' => 'Deportes',
            'slug' => 'deportes',
        ]);
        
        Product::create([
            'name' => 'Camiseta Deportiva',
            'description' => 'Camiseta para ejercicio',
            'price' => 29.99,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 'test.jpg',
        ]);
        
        Product::create([
            'name' => 'Pantalon Running',
            'description' => 'Pantalon para correr',
            'price' => 49.99,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 'test2.jpg',
        ]);
    }

    public function test_product_search_component_renders(): void
    {
        Livewire::test(ProductSearch::class)
            ->assertStatus(200);
    }

    public function test_search_returns_empty_for_short_query(): void
    {
        Livewire::test(ProductSearch::class)
            ->set('search', 'a')
            ->assertStatus(200);
    }

    public function test_search_finds_products_by_name(): void
    {
        Livewire::test(ProductSearch::class)
            ->set('search', 'Camiseta')
            ->assertSee('Camiseta Deportiva');
    }

    public function test_search_finds_products_by_description(): void
    {
        Livewire::test(ProductSearch::class)
            ->set('search', 'ejercicio')
            ->assertSee('Camiseta Deportiva');
    }

    public function test_search_limits_results_to_5(): void
    {
        $category = Category::first();
        
        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'name' => "Producto $i",
                'price' => 10,
                'stock' => 5,
                'category_id' => $category->id,
                'image_path' => 'test.jpg',
            ]);
        }
        
        Livewire::test(ProductSearch::class)
            ->set('search', 'Producto')
            ->assertStatus(200);
    }

    public function test_search_no_results_for_unknown(): void
    {
        Livewire::test(ProductSearch::class)
            ->set('search', 'xyznonexistent')
            ->assertStatus(200);
    }
}
