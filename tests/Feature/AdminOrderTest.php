<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    protected function createOrderWithItems(): Order
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Cat', 'slug' => 'cat']);
        $product = Product::create([
            'name' => 'Prod',
            'price' => 50,
            'stock' => 10,
            'category_id' => $category->id,
            'image_path' => 't.jpg',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'total' => 50,
            'status' => 'pending',
            'date' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 50,
        ]);

        return $order;
    }

    public function test_admin_can_view_order_details(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrderWithItems();

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_order_status_to_shipped(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrderWithItems();

        $response = $this->actingAs($admin)->put(route('admin.orders.updateStatus', $order->id), [
            'status' => 'shipped',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'shipped']);
    }

    public function test_admin_can_update_order_status_to_delivered(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrderWithItems();

        $response = $this->actingAs($admin)->put(route('admin.orders.updateStatus', $order->id), [
            'status' => 'delivered',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'delivered']);
    }

    public function test_admin_can_update_order_status_to_cancelled(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrderWithItems();

        $response = $this->actingAs($admin)->put(route('admin.orders.updateStatus', $order->id), [
            'status' => 'cancelled',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);
    }

    public function test_admin_cannot_set_invalid_status(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrderWithItems();

        $response = $this->actingAs($admin)->put(route('admin.orders.updateStatus', $order->id), [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_admin_can_delete_order(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrderWithItems();
        $orderId = $order->id;

        $response = $this->actingAs($admin)->delete(route('admin.orders.destroy', $orderId));

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $orderId]);
    }

    public function test_order_show_displays_items(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrderWithItems();

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order->id));
        $response->assertStatus(200);
        $response->assertSee('Prod');
    }
}
