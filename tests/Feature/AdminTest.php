<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    // Dashboard
    public function test_guest_cannot_access_admin(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(302);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    // Products CRUD
    public function test_admin_can_view_products_list(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.products.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_view_create_product_form(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.products.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_product(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Test', 'slug' => 'test']);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Nuevo Producto',
            'description' => 'Descripcion',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Nuevo Producto']);
    }

    public function test_admin_can_edit_product(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create([
            'name' => 'Producto Edit',
            'price' => 50,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 'test.jpg',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.products.edit', $product->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_product(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat2', 'slug' => 'cat2']);
        $product = Product::create([
            'name' => 'Producto Update',
            'price' => 50,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 'test.jpg',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'name' => 'Producto Actualizado',
            'price' => 75,
            'stock' => 8,
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Producto Actualizado']);
    }

    public function test_admin_can_delete_product(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat3', 'slug' => 'cat3']);
        $product = Product::create([
            'name' => 'Producto Delete',
            'price' => 50,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 'test.jpg',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product->id));
        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['name' => 'Producto Delete']);
    }

    // Categories CRUD
    public function test_admin_can_view_categories_list(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_view_create_category_form(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.categories.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_category(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Nueva Categoria',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Nueva Categoria']);
    }

    public function test_admin_can_edit_category(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat Edit', 'slug' => 'cat-edit']);

        $response = $this->actingAs($admin)->get(route('admin.categories.edit', $category->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_category(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat Update', 'slug' => 'cat-update']);

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category->id), [
            'name' => 'Categoria Actualizada',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Categoria Actualizada']);
    }

    public function test_admin_can_delete_category(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat Delete', 'slug' => 'cat-delete']);

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category->id));
        $response->assertRedirect(route('admin.categories.index'));
    }

    // Orders
    public function test_admin_can_view_orders_list(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.orders.index'));
        $response->assertStatus(200);
    }

    // Users
    public function test_admin_can_view_users_list(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_edit_user(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = $this->createAdmin();
        Role::firstOrCreate(['name' => 'client']);
        $user = User::factory()->create();
        $user->assignRole('client');

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->id), [
            'role' => 'client',
        ]);

        $response->assertRedirect(route('admin.users.index'));
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user->id));
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    // Reports
    public function test_admin_can_view_sales_report(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.reports.sales'));
        $response->assertStatus(200);
    }

    public function test_admin_can_filter_sales_by_date(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.reports.sales', [
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
        ]));
        $response->assertStatus(200);
    }

    // Validations
    public function test_product_requires_name(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Test', 'slug' => 'test']);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => '',
            'price' => 50,
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_product_requires_valid_price(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat5', 'slug' => 'cat5']);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Producto',
            'price' => -10,
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_category_requires_name(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_cannot_delete_category_with_products(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'CatWithProds', 'slug' => 'cat-with-prods']);

        Product::create([
            'name' => 'ProdInCat',
            'price' => 10,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 'test.jpg',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category->id));
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');
    }

    public function test_cannot_delete_category_with_children(): void
    {
        $admin = $this->createAdmin();
        $parent = Category::create(['name' => 'Parent', 'slug' => 'parent']);
        Category::create(['name' => 'Child', 'slug' => 'child', 'parent_id' => $parent->id]);

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $parent->id));
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');
    }

    public function test_product_requires_positive_stock(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Producto',
            'price' => 50,
            'stock' => -5,
            'category_id' => $category->id,
        ]);

        $response->assertSessionHasErrors('stock');
    }

    public function test_product_requires_valid_category(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Producto',
            'price' => 50,
            'stock' => 10,
            'category_id' => 9999,
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_category_can_have_parent(): void
    {
        $admin = $this->createAdmin();
        $parent = Category::create(['name' => 'Padre', 'slug' => 'padre']);

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Hija',
            'parent_id' => $parent->id,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Hija', 'parent_id' => $parent->id]);
    }

    public function test_category_can_be_active(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Activa',
            'active' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Activa', 'active' => 1]);
    }

    public function test_admin_can_edit_product_with_size_color(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Ropa', 'slug' => 'ropa']);
        $product = Product::create([
            'name' => 'Camiseta',
            'price' => 30,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'name' => 'Camiseta L Azul',
            'price' => 35,
            'stock' => 8,
            'category_id' => $category->id,
            'size' => 'L',
            'color' => 'Azul',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Camiseta L Azul', 'size' => 'L', 'color' => 'Azul']);
    }
}
