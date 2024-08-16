<?php

namespace Tests\Feature;

use App\Models\User;
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
        $this->loginUser('HR', ['view roles']);

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
     * Test user can't view roles without permission.
     * 
     * @return void
     */
    public function test_user_cant_view_roles_without_permission(): void
    {
        $this->loginUser('HR');

        $response = $this->get(route('role'));

        $response->assertForbidden();
    }

    /**
     * Test user can view create role form.
     * 
     * @return void
     */
    public function test_user_can_view_create_role_form(): void
    {
        $this->loginUser('HR', ['view roles', 'create roles']);

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
     * Test user can't view create role form without permission.
     * 
     * @return void
     */
    public function test_user_cant_view_create_role_form_without_permission(): void
    {
        $this->loginUser('HR');

        $response = $this->get(route('role.create'));

        $response->assertForbidden();
    }

    /**
     * Test user can create a role.
     * 
     * @return void
     */
    public function test_user_can_create_a_role(): void
    {
        $user = $this->loginUser('HR', ['view roles', 'create roles']);

        $permission = Permission::create(['name' => 'random permission']);

        $response = $this->post(route('role.store'), [
            'name' => 'Test Role',
            'permissions' => [$permission->id],
        ]);

        $response->assertRedirect(route('role'));
        $response->assertSessionHas('success-swal', 'Role created successfully.');

        $role = Role::query()->where('name', 'Test Role')->first();

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
        $this->assertDatabaseHas('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permission->id]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Create role with name Test Role',
            'subject_type' => Role::class,
            'subject_id' => $role->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
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
        $this->loginUser('HR', ['view roles', 'create roles']);

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
        $this->loginUser('HR', ['view roles', 'create roles']);

        Role::create(['name' => 'Test Role']);
        
        $response = $this->post(route('role.store'), [
            'name' => 'Test Role',
            'permissions' => [1, 2],
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test permission must be existing to create a role.
     * 
     * @return void
     */
    public function test_permission_must_be_existing_to_create_a_role(): void
    {
        $this->loginUser('HR', ['view roles', 'create roles']);

        $response = $this->post(route('role.store'), [
            'name' => 'Test Role',
            'permissions' => [1, 2],
        ]);

        $response->assertSessionHasErrors('permissions.0');

        $this->assertDatabaseMissing('roles', ['name' => 'Test Role']);
    }

    /**
     * Test user can't create a role without permission.
     * 
     * @return void
     */
    public function test_user_cant_create_a_role_without_permission(): void
    {
        $this->loginUser('HR', ['view roles']);

        $response = $this->post(route('role.store'), [
            'name' => 'Test Role',
            'permissions' => [1, 2],
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('roles', ['name' => 'Test Role']);
    }

    /**
     * Test user can view edit role form.
     * 
     * @return void
     */
    public function test_user_can_view_edit_role_form(): void
    {
        $this->loginUser('HR', ['view roles', 'edit roles']);

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
        $role = Role::create(['name' => 'Random Role 9231']);

        $response = $this->get(route('role.edit', $role));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't view edit role form without permission.
     * 
     * @return void
     */
    public function test_user_cant_view_edit_role_form_without_permission(): void
    {
        $this->loginUser('HR', ['view roles']);

        $role = Role::create(['name' => 'Random Role 9231']);

        $response = $this->get(route('role.edit', $role));

        $response->assertForbidden();
    }

    /**
     * Test user can update a role.
     * 
     * @return void
     */
    public function test_user_can_update_a_role(): void
    {
        $user = $this->loginUser('HR', ['view roles', 'edit roles']);

        $role = Role::create(['name' => 'Test Role 123']);

        $permission2 = Permission::create(['name' => 'test create roles']);
        $permission3 = Permission::create(['name' => 'test update roles']);

        $response = $this->put(route('role.update', $role), [
            'name' => 'Updated Role 123',
            'permissions' => [$permission2->id, $permission3->id],
        ]);

        $response->assertRedirect(route('role'));
        $response->assertSessionHas('success-swal', 'Role updated successfully.');

        $this->assertDatabaseHas('roles', ['name' => 'Updated Role 123']);
        $this->assertDatabaseHas('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permission2->id]);
        $this->assertDatabaseHas('role_has_permissions', ['role_id' => $role->id, 'permission_id' => $permission3->id]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Update role from Test Role 123 to Updated Role 123',
            'subject_type' => Role::class,
            'subject_id' => $role->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
    }

    /**
     * Test user can't update a role if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_update_a_role_if_not_authenticated(): void
    {
        $role = Role::create(['name' => 'Test Role ddd']);

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
        $this->loginUser('HR', ['view roles', 'edit roles']);

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
        $this->loginUser('HR', ['view roles', 'edit roles']);

        Role::create(['name' => 'Updated Role']);

        $role = Role::create(['name' => 'Test Role again']);

        $response = $this->put(route('role.update', $role), [
            'name' => 'Updated Role',
            'permissions' => [1, 2],
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test permission must be existing to update a role.
     * 
     * @return void
     */
    public function test_permission_must_be_existing_to_update_a_role(): void
    {
        $this->loginUser('HR', ['view roles', 'edit roles']);

        $role = Role::create(['name' => 'Test Role']);

        $response = $this->put(route('role.update', $role), [
            'name' => 'Updated Role',
            'permissions' => [9999],
        ]);

        $response->assertSessionHasErrors('permissions.0');

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
    }

    /**
     * Test user can't update a role without permission.
     * 
     * @return void
     */
    public function test_user_cant_update_a_role_without_permission(): void
    {
        $this->loginUser('HR', ['view roles']);

        $role = Role::create(['name' => 'Test Role']);

        $response = $this->put(route('role.update', $role), [
            'name' => 'Updated Role',
            'permissions' => [1, 2],
        ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
    }

    /**
     * Test user can view role details if authenticated.
     * 
     * @return void
     */
    public function test_user_can_view_role_details_if_authenticated(): void
    {
        $this->loginUser('HR', ['view roles']);

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
     * Test user can't view role details without permission.
     * 
     * @return void
     */
    public function test_user_cant_view_role_details_without_permission(): void
    {
        $this->loginUser('HR');

        $role = Role::create(['name' => 'Test Role']);

        $response = $this->get(route('role.show', $role));

        $response->assertForbidden();
    }

    /**
     * Test user can delete a role.
     * 
     * @return void
     */
    public function test_user_can_delete_a_role(): void
    {
        $user = $this->loginUser('HR', ['view roles', 'delete roles']);

        $role = Role::create(['name' => 'Test Role']);
        $savedRole = $role->replicate();

        $response = $this->delete(route('role.delete', $role));

        $response->assertRedirect(route('role'));
        $response->assertSessionHas('success-swal', 'Role deleted successfully.');

        $this->assertDatabaseMissing('roles', ['name' => 'Test Role']);

        $this->assertDatabaseMissing(config('activitylog.table_name'), [
            'description' => 'Delete role with name Test Role',
            'subject_type' => Role::class,
            'subject_id' => $savedRole->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
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

    /**
     * Test user can't delete a role without permission.
     * 
     * @return void
     */
    public function test_user_cant_delete_a_role_without_permission(): void
    {
        $this->loginUser('HR', ['view roles']);

        $role = Role::create(['name' => 'Test Role']);

        $response = $this->delete(route('role.delete', $role));

        $response->assertForbidden();

        $this->assertDatabaseHas('roles', ['name' => 'Test Role']);
    }
}
