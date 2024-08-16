<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Login User
     * 
     * @param string $role
     * @param array{string} | string | null $permission
     * @return User
     */
    public function loginUser(string $role, array | string | null $permission = null): User
    {
        $user = $this->createUser($role, $permission);

        $this->actingAs($user);

        return $user;
    }

    /**
     * Create a user with a role and permission
     *
     * @param string $role
     * @param array{string} | string | null $permission
     * @return User
     */
    public function createUser(string $role, array | string | null $permission = null): User
    {
        $role = Role::create(['name' => $role]);

        if($permission) {
            if(is_array($permission)) {
                foreach($permission as $p) {
                    $permission = Permission::create(['name' => $p]);
                    $role->givePermissionTo($permission->name);
                }
            }

            if(is_string($permission)) {
                $permission = Permission::create(['name' => $permission]);
                $role->givePermissionTo($permission->name);
            }
        }

        $user = User::factory()->create();

        $user->assignRole($role->name);

        return $user;
    }
}
