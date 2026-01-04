<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class AdminCategoryController2Test extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');
        return $user;
    }

    public function test_admin_can_view_create_form(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get(route('admin.categories.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_view_edit_form(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Test', 'slug' => 'test']);
        
        $response = $this->actingAs($admin)->get(route('admin.categories.edit', $category->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_category(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Old', 'slug' => 'old']);
        
        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category->id), [
            'name' => 'Updated',
        ]);
        
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Updated']);
    }

    public function test_admin_can_create_subcategory(): void
    {
        $admin = $this->createAdmin();
        $parent = Category::create(['name' => 'Parent', 'slug' => 'parent']);
        
        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Child',
            'parent_id' => $parent->id,
        ]);
        
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Child',
            'parent_id' => $parent->id,
        ]);
    }
}
