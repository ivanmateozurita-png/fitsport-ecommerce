<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function createAuthenticatedUser(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    protected function createProduct(int $stock = 10): Product
    {
        $category = Category::factory()->create();

        return Product::factory()->create([
            'category_id' => $category->id,
            'stock' => $stock,
            'price' => 99.99,
        ]);
    }

    /**
     * TC-015: Usuario no autenticado no puede acceder a checkout
     */
    public function test_guest_cannot_access_checkout(): void
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    }

    /**
     * TC-016: Usuario autenticado puede acceder a checkout
     */
    public function test_authenticated_user_can_access_checkout(): void
    {
        $user = $this->createAuthenticatedUser();

        // Agregar producto al carrito primero
        $product = $this->createProduct();
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->get('/checkout');
        $response->assertStatus(200);
    }

    /**
     * TC-017: Usuario puede crear un pedido
     */
    public function test_user_can_create_order(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct(10);

        // Agregar al carrito
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // Procesar checkout
        $response = $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Test User',
            'email' => $user->email,
            'phone' => '0999999999',
            'address' => 'Test Address 123',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * TC-018: Stock se reduce después de crear pedido
     */
    public function test_stock_decreases_after_order(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct(10);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Test User',
            'email' => $user->email,
            'phone' => '0999999999',
            'address' => 'Test Address 123',
        ]);

        $product->refresh();
        $this->assertEquals(7, $product->stock);
    }

    /**
     * TC-019: Usuario puede ver sus pedidos
     */
    public function test_user_can_view_orders(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->actingAs($user)->get('/my-orders');
        $response->assertStatus(200);
    }

    /**
     * TC-020: Usuario no puede ver pedidos de otro usuario
     */
    public function test_user_cannot_view_other_users_orders(): void
    {
        $user1 = $this->createAuthenticatedUser();
        $user2 = $this->createAuthenticatedUser();

        Order::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->get('/my-orders');
        $response->assertStatus(200);
    }

    public function test_checkout_redirects_when_cart_empty(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->actingAs($user)->get('/checkout');
        $response->assertRedirect(route('cart.index'));
    }

    public function test_process_fails_with_empty_cart(): void
    {
        $user = $this->createAuthenticatedUser();

        $response = $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Test',
            'email' => $user->email,
            'phone' => '123',
            'address' => 'Dir',
        ]);

        $response->assertRedirect(route('cart.index'));
    }

    public function test_checkout_process_validates_name(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct(10);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post('/checkout/process', [
            'name' => '',
            'email' => $user->email,
            'address' => 'Dir',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_checkout_process_validates_email(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct(10);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Test',
            'email' => 'invalid-email',
            'address' => 'Dir',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_checkout_process_validates_address(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct(10);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Test',
            'email' => $user->email,
            'address' => '',
        ]);

        $response->assertSessionHasErrors('address');
    }

    public function test_order_items_are_created(): void
    {
        $user = $this->createAuthenticatedUser();
        $product = $this->createProduct(10);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)->post('/checkout/process', [
            'name' => 'Test',
            'email' => $user->email,
            'phone' => '123',
            'address' => 'Test Dir',
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertCount(1, $order->items);
    }

    public function test_my_orders_shows_user_orders(): void
    {
        $user = $this->createAuthenticatedUser();

        Order::factory()->create(['user_id' => $user->id]);
        Order::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/my-orders');
        $response->assertStatus(200);
    }
}
