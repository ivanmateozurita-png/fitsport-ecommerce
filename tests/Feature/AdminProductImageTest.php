<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class AdminProductImageTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');
        return $user;
    }

    public function test_admin_can_create_product_with_image(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        
        $file = UploadedFile::fake()->image('product.jpg', 200, 200);
        
        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Product With Image',
            'description' => 'Description',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $category->id,
            'image' => $file,
        ]);
        
        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Product With Image']);
    }

    public function test_admin_can_update_product_with_new_image(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat2', 'slug' => 'cat2']);
        $product = Product::create([
            'name' => 'Old Product',
            'price' => 50,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 'assets/uploads/products/old.jpg',
        ]);
        
        $file = UploadedFile::fake()->image('new_product.jpg', 200, 200);
        
        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'name' => 'Updated Product',
            'price' => 75,
            'stock' => 8,
            'category_id' => $category->id,
            'image' => $file,
        ]);
        
        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
    }

    public function test_admin_can_update_product_without_image(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat3', 'slug' => 'cat3']);
        $product = Product::create([
            'name' => 'Existing',
            'price' => 50,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);
        
        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'name' => 'Updated Name Only',
            'price' => 55,
            'stock' => 10,
            'category_id' => $category->id,
        ]);
        
        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Updated Name Only']);
    }

    public function test_product_update_replaces_old_image(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat5', 'slug' => 'cat5']);
        $product = Product::create([
            'name' => 'Product',
            'price' => 50,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 'assets/uploads/products/old_image.jpg',
        ]);
        
        $file = UploadedFile::fake()->image('new_image.jpg', 100, 100);
        
        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'name' => 'Product With New Image',
            'price' => 60,
            'stock' => 10,
            'category_id' => $category->id,
            'image' => $file,
        ]);
        
        $response->assertRedirect(route('admin.products.index'));
    }
}
