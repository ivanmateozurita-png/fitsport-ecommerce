<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_user_and_profile(): void
    {
        $response = $this->post('/register', [
            'nombre_completo' => 'Test User',
            'email' => 'testuser@gmail.com',
            'telefono' => '0991234567',
            'direccion' => 'Test Address 123',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', ['email' => 'testuser@gmail.com']);
        $this->assertDatabaseHas('profiles', ['phone' => '0991234567']);
    }

    public function test_registration_blocks_tempmail_domain(): void
    {
        $response = $this->post('/register', [
            'nombre_completo' => 'Temp User',
            'email' => 'fake@tempmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_blocks_guerrillamail_domain(): void
    {
        $response = $this->post('/register', [
            'nombre_completo' => 'Guerrilla User',
            'email' => 'test@guerrillamail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_blocks_mailinator_domain(): void
    {
        $response = $this->post('/register', [
            'nombre_completo' => 'Mail User',
            'email' => 'test@mailinator.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_blocks_yopmail_domain(): void
    {
        $response = $this->post('/register', [
            'nombre_completo' => 'Yop User',
            'email' => 'test@yopmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'nombre_completo' => 'Test',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this->post('/register', [
            'nombre_completo' => 'Test',
            'email' => 'not-an-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registration_creates_client_role_profile(): void
    {
        $this->post('/register', [
            'nombre_completo' => 'Client Test',
            'email' => 'client@gmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::where('email', 'client@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('client', $user->profile->role);
    }
}
