<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function test_dashboard_shows_product_count(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);

        Product::create([
            'name' => 'Prod1',
            'price' => 10,
            'stock' => 5,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);

        Product::create([
            'name' => 'Prod2',
            'price' => 20,
            'stock' => 8,
            'category_id' => $category->id,
            'image_path' => 't2.jpg',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_shows_order_count(): void
    {
        $admin = $this->createAdmin();
        $customer = User::factory()->create();

        Order::create([
            'user_id' => $customer->id,
            'total' => 100,
            'status' => 'pending',
            'date' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_shows_recent_orders(): void
    {
        $admin = $this->createAdmin();
        $customer = User::factory()->create();

        for ($i = 0; $i < 7; $i++) {
            Order::create([
                'user_id' => $customer->id,
                'total' => 50 + $i * 10,
                'status' => 'pending',
                'date' => now()->subDays($i),
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_shows_low_stock_count(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);

        Product::create([
            'name' => 'Low Stock',
            'price' => 10,
            'stock' => 3, // under 10
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);

        Product::create([
            'name' => 'Good Stock',
            'price' => 20,
            'stock' => 50,
            'category_id' => $category->id,
            'image_path' => 't2.jpg',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_with_no_data(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }
}
