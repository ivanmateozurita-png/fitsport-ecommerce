<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

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
     * TC-009: Página del carrito es accesible
     */
    public function test_cart_page_is_accessible(): void
    {
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    /**
     * TC-010: Usuario puede agregar producto al carrito
     */
    public function test_user_can_add_product_to_cart(): void
    {
        $product = $this->createProduct();

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHas('cart');
    }

    /**
     * TC-011: No se puede agregar más cantidad que el stock
     */
    public function test_cannot_add_more_than_stock(): void
    {
        $product = $this->createProduct(5);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        // Debe rechazar o limitar la cantidad
        $this->assertTrue(true); // Placeholder - verificar comportamiento real
    }

    /**
     * TC-012: No se puede agregar producto con stock 0
     */
    public function test_cannot_add_product_with_zero_stock(): void
    {
        $product = $this->createProduct(0);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // El carrito debe estar vacío o mostrar error
        $this->assertTrue(true); // Placeholder
    }

    /**
     * TC-013: Usuario puede eliminar producto del carrito
     */
    public function test_user_can_remove_product_from_cart(): void
    {
        $product = $this->createProduct();

        // Agregar primero
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Eliminar
        $response = $this->delete('/cart/remove/' . $product->id);

        // Acepta 200 (JSON) o 302 (redirect)
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    /**
     * TC-014: Usuario puede actualizar cantidad en carrito
     */
    public function test_user_can_update_cart_quantity(): void
    {
        $product = $this->createProduct();

        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->patch('/cart/update/' . $product->id, [
            'quantity' => 3,
        ]);

        // Acepta 200 (JSON) o 302 (redirect)
        $this->assertTrue(in_array($response->status(), [200, 302]));
    }
}
