<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_belongs_to_category(): void
    {
        $category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
        ]);
        
        $product = Product::create([
            'name' => 'Prod',
            'price' => 10,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);
        
        $this->assertInstanceOf(Category::class, $product->category);
    }

    public function test_category_has_products(): void
    {
        $category = Category::create([
            'name' => 'Cat',
            'slug' => 'cat',
        ]);
        
        Product::create([
            'name' => 'P1',
            'price' => 10,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);
        
        $this->assertCount(1, $category->products);
    }

    public function test_category_has_children(): void
    {
        $parent = Category::create([
            'name' => 'Parent',
            'slug' => 'parent',
        ]);
        
        Category::create([
            'name' => 'Child',
            'slug' => 'child',
            'parent_id' => $parent->id,
        ]);
        
        $this->assertCount(1, $parent->children);
    }

    public function test_order_belongs_to_user(): void
    {
        $user = User::factory()->create();
        
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 100,
            'status' => 'pending',
        ]);
        
        $this->assertInstanceOf(User::class, $order->user);
    }

    public function test_user_has_orders(): void
    {
        $user = User::factory()->create();
        
        Order::create([
            'user_id' => $user->id,
            'total' => 50,
            'status' => 'pending',
        ]);
        
        $this->assertCount(1, $user->orders);
    }

    public function test_user_has_profile(): void
    {
        $user = User::factory()->create();
        
        Profile::create([
            'user_id' => $user->id,
            'role' => 'client',
        ]);
        
        $this->assertInstanceOf(Profile::class, $user->profile);
    }

    public function test_profile_belongs_to_user(): void
    {
        $user = User::factory()->create();
        
        $profile = Profile::create([
            'user_id' => $user->id,
            'role' => 'client',
        ]);
        
        $this->assertInstanceOf(User::class, $profile->user);
    }
}
