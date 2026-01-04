<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    public function test_reset_password_link_fails_with_invalid_email(): void
    {
        $response = $this->post('/forgot-password', ['email' => 'invalid-format']);
        $response->assertSessionHasErrors('email');
    }

    public function test_reset_requires_email(): void
    {
        $response = $this->post('/forgot-password', ['email' => '']);
        $response->assertSessionHasErrors('email');
    }

    public function test_reset_with_nonexistent_email(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertRedirect();
    }
}
