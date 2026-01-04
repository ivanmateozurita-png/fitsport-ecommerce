<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get(route('profile.show'));
        
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_view_profile(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('profile.show'));
        
        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
    }

    public function test_user_can_view_edit_profile(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('profile.edit'));
        
        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();
        
        Profile::create([
            'user_id' => $user->id,
            'role' => 'client',
        ]);
        
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nombre Actualizado',
            'phone' => '1234567890',
            'address' => 'Calle Test 123',
            'city' => 'Ciudad Test',
        ]);
        
        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', ['name' => 'Nombre Actualizado']);
    }

    public function test_profile_created_automatically_on_show(): void
    {
        $user = User::factory()->create();
        
        // Sin perfil existente
        $this->assertNull($user->profile);
        
        // Visitar perfil crea uno automáticamente
        $response = $this->actingAs($user)->get(route('profile.show'));
        $response->assertStatus(200);
        
        $user->refresh();
        $this->assertNotNull($user->profile);
    }

    public function test_profile_created_automatically_on_edit(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);
        
        $user->refresh();
        $this->assertNotNull($user->profile);
    }

    public function test_update_creates_profile_if_not_exists(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nuevo Nombre',
            'phone' => '999888777',
            'address' => 'Nueva Dir',
            'city' => 'Nueva Ciudad',
        ]);
        
        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('profiles', ['user_id' => $user->id]);
    }

    public function test_profile_update_validates_name(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => '',
        ]);
        
        $response->assertSessionHasErrors('name');
    }

    public function test_profile_can_upload_image(): void
    {
        $user = User::factory()->create();
        
        Profile::create([
            'user_id' => $user->id,
            'role' => 'client',
        ]);
        
        Storage::fake('public');
        
        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);
        
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Test User',
            'phone' => '123456',
            'address' => 'Test Address',
            'city' => 'Test City',
            'image' => $file,
        ]);
        
        $response->assertRedirect(route('profile.show'));
    }

    public function test_profile_update_with_all_fields(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Full Name Update',
            'phone' => '0999111222',
            'address' => 'Complete Address 123',
            'city' => 'Full City Name',
        ]);
        
        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', ['name' => 'Full Name Update']);
    }
}
