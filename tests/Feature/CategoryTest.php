<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);
        
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_category_can_have_parent(): void
    {
        $parent = Category::create(['name' => 'Parent', 'slug' => 'parent']);
        $child = Category::create([
            'name' => 'Child',
            'slug' => 'child',
            'parent_id' => $parent->id,
        ]);
        
        $this->assertEquals($parent->id, $child->parent_id);
        $this->assertInstanceOf(Category::class, $child->parent);
    }

    public function test_category_children_relationship(): void
    {
        $parent = Category::create(['name' => 'Parent2', 'slug' => 'parent2']);
        Category::create(['name' => 'Child1', 'slug' => 'child1', 'parent_id' => $parent->id]);
        Category::create(['name' => 'Child2', 'slug' => 'child2', 'parent_id' => $parent->id]);
        
        $this->assertCount(2, $parent->children);
    }

    public function test_category_active_scope(): void
    {
        Category::create(['name' => 'Active', 'slug' => 'active', 'active' => 1]);
        Category::create(['name' => 'Inactive', 'slug' => 'inactive', 'active' => 0]);
        
        $categories = Category::where('active', 1)->get();
        $this->assertCount(1, $categories);
    }

    public function test_category_products_relationship(): void
    {
        $category = Category::create(['name' => 'CatProd', 'slug' => 'catprod']);
        
        \App\Models\Product::create([
            'name' => 'Prod1',
            'price' => 10,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);
        
        $this->assertCount(1, $category->products);
    }
}
