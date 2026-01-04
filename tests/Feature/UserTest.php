<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_user_has_orders_relationship(): void
    {
        $user = User::factory()->create();

        \App\Models\Order::create([
            'user_id' => $user->id,
            'total' => 100,
            'status' => 'pending',
        ]);

        $this->assertCount(1, $user->orders);
    }

    public function test_user_has_profile_relationship(): void
    {
        $user = User::factory()->create();

        \App\Models\Profile::create([
            'user_id' => $user->id,
            'role' => 'client',
        ]);

        $this->assertNotNull($user->profile);
    }

    public function test_user_can_have_multiple_orders(): void
    {
        $user = User::factory()->create();

        \App\Models\Order::create([
            'user_id' => $user->id,
            'total' => 50,
            'status' => 'pending',
        ]);

        \App\Models\Order::create([
            'user_id' => $user->id,
            'total' => 75,
            'status' => 'delivered',
        ]);

        $this->assertCount(2, $user->orders);
    }

    public function test_user_email_verified_at(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertTrue($user->hasVerifiedEmail());
    }

    public function test_unverified_user(): void
    {
        $user = User::factory()->unverified()->create();

        $this->assertFalse($user->hasVerifiedEmail());
    }
}
