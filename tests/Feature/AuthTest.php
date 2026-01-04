<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TC-001: Página de login accesible
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * TC-002: Página de registro accesible
     */
    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * TC-003: Usuario puede registrarse
     */
    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // El registro redirige a home o dashboard
        $response->assertRedirect();
    }

    /**
     * TC-004: Usuario puede iniciar sesión
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
    }

    /**
     * TC-005: Usuario con credenciales incorrectas no puede entrar
     */
    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
    }

    /**
     * TC-006: Usuario puede cerrar sesión
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }

    /**
     * TC-007: Rutas protegidas redirigen a login
     */
    public function test_protected_routes_redirect_to_login(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    /**
     * TC-008: Email duplicado no permite registro
     */
    public function test_duplicate_email_cannot_register(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_forgot_password_page_accessible(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    public function test_password_reset_request_validates_email(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => '',
        ]);
        $response->assertSessionHasErrors('email');
    }

    public function test_password_reset_with_invalid_email(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);
        $response->assertRedirect();
    }
}
