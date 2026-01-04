<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_belongs_to_category(): void
    {
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);

        $product = Product::create([
            'name' => 'Prod',
            'price' => 100,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
    }

    public function test_product_has_price(): void
    {
        $category = Category::create(['name' => 'Cat2', 'slug' => 'cat2']);

        $product = Product::create([
            'name' => 'Prod2',
            'price' => 99.99,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 't2.jpg',
        ]);

        $this->assertEquals(99.99, $product->price);
    }

    public function test_product_has_stock(): void
    {
        $category = Category::create(['name' => 'Cat3', 'slug' => 'cat3']);

        $product = Product::create([
            'name' => 'Prod3',
            'price' => 50,
            'stock' => 15,
            'category_id' => $category->id,
            'image_path' => 't3.jpg',
        ]);

        $this->assertEquals(15, $product->stock);
    }

    public function test_product_can_decrement_stock(): void
    {
        $category = Category::create(['name' => 'Cat4', 'slug' => 'cat4']);

        $product = Product::create([
            'name' => 'Prod4',
            'price' => 50,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't4.jpg',
        ]);

        $product->decrement('stock', 3);
        $product->refresh();

        $this->assertEquals(7, $product->stock);
    }

    public function test_product_has_image_path(): void
    {
        $category = Category::create(['name' => 'Cat5', 'slug' => 'cat5']);

        $product = Product::create([
            'name' => 'Prod5',
            'price' => 50,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 'images/product.jpg',
        ]);

        $this->assertEquals('images/product.jpg', $product->image_path);
    }

    public function test_product_can_have_size(): void
    {
        $category = Category::create(['name' => 'Cat6', 'slug' => 'cat6']);

        $product = Product::create([
            'name' => 'Prod6',
            'price' => 50,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't6.jpg',
            'size' => 'M',
        ]);

        $this->assertEquals('M', $product->size);
    }

    public function test_product_can_have_color(): void
    {
        $category = Category::create(['name' => 'Cat7', 'slug' => 'cat7']);

        $product = Product::create([
            'name' => 'Prod7',
            'price' => 50,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't7.jpg',
            'color' => 'Rojo',
        ]);

        $this->assertEquals('Rojo', $product->color);
    }

    public function test_product_can_have_description(): void
    {
        $category = Category::create(['name' => 'Cat8', 'slug' => 'cat8']);

        $product = Product::create([
            'name' => 'Prod8',
            'description' => 'Descripcion del producto',
            'price' => 50,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't8.jpg',
        ]);

        $this->assertEquals('Descripcion del producto', $product->description);
    }
}
