<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /**
     * Test user can view roles.
     * 
     * @return void
     */
    public function test_user_can_view_roles(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->get(route('role'));

        $response->assertStatus(200);
        $response->assertViewIs('roles.index');
        $response->assertViewHas('roles');
    }

    /**
     * Test user can't view roles if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_view_roles_if_not_authenticated(): void
    {
        $response = $this->get(route('role'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can view create role form.
     * 
     * @return void
     */
    public function test_user_can_view_create_role_form(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->get(route('role.create'));

        $response->assertStatus(200);
        $response->assertViewIs('roles.create');
        $response->assertViewHas('permissions');
    }

    /**
     * Test user can't view create role form if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_view_create_role_form_if_not_authenticated(): void
    {
        $response = $this->get(route('role.create'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can create a role.
     * 
     * @return void
     */
    public function test_user_can_create_a_role(): void
    {
        $this->actingAs($this->createUser());

        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'create roles']);

        $response = $this->post(route('role.store'), [
            'name' => 'Test Role',
            'permissions' => [1, 2],
        ]);

        $response->assertRedirect(route('role'));
        $response->assertSessionHas('success-swal', 'Role created successfully.');

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
        $this->assertDatabaseHas('role_has_permissions', ['role_id' => 1, 'permission_id' => 1]);
        $this->assertDatabaseHas('role_has_permissions', ['role_id' => 1, 'permission_id' => 2]);
    }

    /**
     * Test user can't create a role if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_create_a_role_if_not_authenticated(): void
    {
        $response = $this->post(route('role.store'), [
            'name' => 'Test Role',
            'permissions' => [1, 2],
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('roles', ['name' => 'Test Role']);
    }

    /**
     * Test name and permissions are required to create a role.
     * 
     * @return void
     */
    public function test_name_and_permissions_are_required_to_create_a_role(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->post(route('role.store'), []);

        $response->assertSessionHasErrors(['name', 'permissions']);

        $this->assertDatabaseMissing('roles', ['name' => 'Test Role']);
    }

    /**
     * Test name must be unique to create a role.
     * 
     * @return void
     */
    public function test_name_must_be_unique_to_create_a_role(): void
    {
        $this->actingAs($this->createUser());

        Role::create(['name' => 'Test Role']);
        
        $response = $this->post(route('role.store'), [
            'name' => 'Test Role',
            'permissions' => [1, 2],
        ]);

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseCount('roles', 1);
    }

    /**
     * Test permission must be existing to create a role.
     * 
     * @return void
     */
    public function test_permission_must_be_existing_to_create_a_role(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->post(route('role.store'), [
            'name' => 'Test Role',
            'permissions' => [1, 2],
        ]);

        $response->assertSessionHasErrors('permissions.0');

        $this->assertDatabaseMissing('roles', ['name' => 'Test Role']);
    }

    /**
     * Test user can view edit role form.
     * 
     * @return void
     */
    public function test_user_can_view_edit_role_form(): void
    {
        $this->actingAs($this->createUser());

        $role = Role::create(['name' => 'Test Role']);

        $response = $this->get(route('role.edit', $role));

        $response->assertStatus(200);
        $response->assertViewIs('roles.edit');
        $response->assertViewHas('role');
        $response->assertViewHas('permissions');
    }

    /**
     * Test user can't view edit role form if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_view_edit_role_form_if_not_authenticated(): void
    {
        $role = Role::create(['name' => 'Test Role']);

        $response = $this->get(route('role.edit', $role));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can update a role.
     * 
     * @return void
     */
    public function test_user_can_update_a_role(): void
    {
        $this->actingAs($this->createUser());

        $role = Role::create(['name' => 'Test Role 123']);

        $permission1 = Permission::create(['name' => 'test view roles']);
        $permission2 = Permission::create(['name' => 'test create roles']);
        $permission3 = Permission::create(['name' => 'test update roles']);

        $response = $this->put(route('role.update', $role), [
            'name' => 'Updated Role 123',
            'permissions' => [$permission1->id, $permission2->id, $permission3->id],
        ]);

        $response->assertRedirect(route('role'));
        $response->assertSessionHas('success-swal', 'Role updated successfully.');

        $this->assertDatabaseHas('roles', ['name' => 'Updated Role 123']);
        $this->assertDatabaseHas('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permission1->id]);
        $this->assertDatabaseHas('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permission2->id]);
        $this->assertDatabaseHas('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permission3->id]);
    }

    /**
     * Test user can't update a role if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_update_a_role_if_not_authenticated(): void
    {
        $role = Role::create(['name' => 'Test Role']);

        $response = $this->put(route('role.update', $role), [
            'name' => 'Updated Role',
            'permissions' => [1, 2, 3],
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('roles', ['name' => 'Updated Role']);
    }

    /**
     * Test name and permissions are required to update a role.
     * 
     * @return void
     */
    public function test_name_and_permissions_are_required_to_update_a_role(): void
    {
        $this->actingAs($this->createUser());

        $role = Role::create(['name' => 'Test Role']);

        $response = $this->put(route('role.update', $role), []);

        $response->assertSessionHasErrors(['name', 'permissions']);

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
    }

    /**
     * Test name must be unique to update a role.
     * 
     * @return void
     */
    public function test_name_must_be_unique_to_update_a_role(): void
    {
        $this->actingAs($this->createUser());

        Role::create(['name' => 'Updated Role']);

        $role = Role::create(['name' => 'Test Role again']);

        $response = $this->put(route('role.update', $role), [
            'name' => 'Updated Role',
            'permissions' => [1, 2],
        ]);

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseCount('roles', 2);
    }

    /**
     * Test user can view role details if authenticated.
     * 
     * @return void
     */
    public function test_user_can_view_role_details_if_authenticated(): void
    {
        $this->actingAs($this->createUser());

        $role = Role::create(['name' => 'Test Role']);

        $response = $this->get(route('role.show', $role));

        $response->assertStatus(200);
        $response->assertViewIs('roles.show');
        $response->assertViewHas('role');
    }

    /**
     * Test user can't view role details if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_view_role_details_if_not_authenticated(): void
    {
        $role = Role::create(['name' => 'Test Role']);

        $response = $this->get(route('role.show', $role));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can delete a role.
     * 
     * @return void
     */
    public function test_user_can_delete_a_role(): void
    {
        $this->actingAs($this->createUser());

        $role = Role::create(['name' => 'Test Role']);

        $response = $this->delete(route('role.delete', $role));

        $response->assertRedirect(route('role'));
        $response->assertSessionHas('success-swal', 'Role deleted successfully.');

        $this->assertDatabaseMissing('roles', ['name' => 'Test Role']);
    }

    /**
     * Test user can't delete a role if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_delete_a_role_if_not_authenticated(): void
    {
        $role = Role::create(['name' => 'Test Role']);

        $response = $this->delete(route('role.delete', $role));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
    }
}
