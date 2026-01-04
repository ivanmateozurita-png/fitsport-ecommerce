<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminCategoryFullTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function test_admin_can_create_category_with_description(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'With Description',
            'description' => 'Esta es una descripcion detallada de la categoria',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'With Description',
            'description' => 'Esta es una descripcion detallada de la categoria',
        ]);
    }

    public function test_admin_can_update_category_with_description(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Old', 'slug' => 'old']);

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category->id), [
            'name' => 'Updated',
            'description' => 'Nueva descripcion',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Updated',
            'description' => 'Nueva descripcion',
        ]);
    }

    public function test_admin_can_update_category_active_status(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'Inactive', 'slug' => 'inactive', 'active' => 0]);

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category->id), [
            'name' => 'Now Active',
            'active' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Now Active', 'active' => 1]);
    }

    public function test_admin_can_delete_empty_category(): void
    {
        $admin = $this->createAdmin();
        $category = Category::create(['name' => 'ToDelete', 'slug' => 'todelete']);
        $id = $category->id;

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $id));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $id]);
    }

    public function test_admin_can_set_parent_on_update(): void
    {
        $admin = $this->createAdmin();
        $parent = Category::create(['name' => 'ParentCat', 'slug' => 'parentcat']);
        $child = Category::create(['name' => 'ChildCat', 'slug' => 'childcat']);

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $child->id), [
            'name' => 'ChildCat',
            'parent_id' => $parent->id,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'ChildCat', 'parent_id' => $parent->id]);
    }
}
