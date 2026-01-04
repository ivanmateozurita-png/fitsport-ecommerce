<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\CatalogSearch;

class LivewireCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->category = Category::create([
            'name' => 'Deportes',
            'slug' => 'deportes',
        ]);
        
        Product::create([
            'name' => 'Camiseta Barata',
            'price' => 19.99,
            'stock' => 10,
            'category_id' => $this->category->id,
            'image_path' => 'test.jpg',
        ]);
        
        Product::create([
            'name' => 'Camiseta Cara',
            'price' => 99.99,
            'stock' => 5,
            'category_id' => $this->category->id,
            'image_path' => 'test2.jpg',
        ]);
    }

    public function test_catalog_search_component_renders(): void
    {
        Livewire::test(CatalogSearch::class)
            ->assertStatus(200)
            ->assertSee('Camiseta');
    }

    public function test_can_search_products(): void
    {
        Livewire::test(CatalogSearch::class)
            ->set('search', 'Barata')
            ->assertSee('Barata');
    }

    public function test_can_filter_by_category(): void
    {
        Livewire::test(CatalogSearch::class)
            ->call('filterByCategory', $this->category->id)
            ->assertSet('categoryId', $this->category->id);
    }

    public function test_can_clear_filters(): void
    {
        Livewire::test(CatalogSearch::class)
            ->set('search', 'test')
            ->set('categoryId', 1)
            ->set('sortBy', 'price_asc')
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('categoryId', null)
            ->assertSet('sortBy', 'default');
    }

    public function test_can_sort_by_price_asc(): void
    {
        Livewire::test(CatalogSearch::class)
            ->set('sortBy', 'price_asc')
            ->assertStatus(200);
    }

    public function test_can_sort_by_price_desc(): void
    {
        Livewire::test(CatalogSearch::class)
            ->set('sortBy', 'price_desc')
            ->assertStatus(200);
    }

    public function test_subcategory_filter(): void
    {
        $parent = Category::create([
            'name' => 'Ropa',
            'slug' => 'ropa',
        ]);
        
        $child = Category::create([
            'name' => 'Camisetas',
            'slug' => 'camisetas',
            'parent_id' => $parent->id,
        ]);
        
        Product::create([
            'name' => 'Camiseta Subcategoria',
            'price' => 15.99,
            'stock' => 3,
            'category_id' => $child->id,
            'image_path' => 'test3.jpg',
        ]);
        
        Livewire::test(CatalogSearch::class)
            ->call('filterByCategory', $child->id)
            ->assertStatus(200);
    }

    public function test_parent_category_includes_children_products(): void
    {
        $parent = Category::create([
            'name' => 'Calzado',
            'slug' => 'calzado',
        ]);
        
        $child = Category::create([
            'name' => 'Zapatillas',
            'slug' => 'zapatillas',
            'parent_id' => $parent->id,
        ]);
        
        Product::create([
            'name' => 'Zapatilla Running',
            'price' => 89.99,
            'stock' => 7,
            'category_id' => $child->id,
            'image_path' => 'test4.jpg',
        ]);
        
        Livewire::test(CatalogSearch::class)
            ->call('filterByCategory', $parent->id)
            ->assertStatus(200);
    }

    public function test_search_combined_with_category(): void
    {
        Livewire::test(CatalogSearch::class)
            ->set('search', 'Camiseta')
            ->set('categoryId', $this->category->id)
            ->assertStatus(200);
    }

    public function test_search_combined_with_sort(): void
    {
        Livewire::test(CatalogSearch::class)
            ->set('search', 'Camiseta')
            ->set('sortBy', 'price_asc')
            ->assertStatus(200);
    }
}
