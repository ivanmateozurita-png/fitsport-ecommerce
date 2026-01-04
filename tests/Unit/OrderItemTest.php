<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_item_belongs_to_order(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        
        $product = Product::create([
            'name' => 'Prod',
            'price' => 100,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 100,
            'status' => 'pending',
        ]);
        
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 100,
        ]);
        
        $this->assertInstanceOf(Order::class, $item->order);
    }

    public function test_order_item_belongs_to_product(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Cat2', 'slug' => 'cat2']);
        
        $product = Product::create([
            'name' => 'Prod2',
            'price' => 50,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 't2.jpg',
        ]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 50,
            'status' => 'pending',
        ]);
        
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 50,
        ]);
        
        $this->assertInstanceOf(Product::class, $item->product);
    }

    public function test_order_item_subtotal(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Cat3', 'slug' => 'cat3']);
        
        $product = Product::create([
            'name' => 'Prod3',
            'price' => 25,
            'stock' => 20,
            'category_id' => $category->id,
            'image_path' => 't3.jpg',
        ]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 75,
            'status' => 'pending',
        ]);
        
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'unit_price' => 25,
        ]);
        
        $expected = 25 * 3;
        $this->assertEquals($expected, $item->unit_price * $item->quantity);
    }

    public function test_order_has_multiple_items(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Cat4', 'slug' => 'cat4']);
        
        $product1 = Product::create([
            'name' => 'Prod4',
            'price' => 10,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't4.jpg',
        ]);
        
        $product2 = Product::create([
            'name' => 'Prod5',
            'price' => 20,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't5.jpg',
        ]);
        
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 30,
            'status' => 'pending',
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'quantity' => 1,
            'unit_price' => 10,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'unit_price' => 20,
        ]);
        
        $this->assertCount(2, $order->items);
    }
}
