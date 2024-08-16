<?php

namespace App\Actions\Roles;

use App\Actions\Action;
use App\DataTransferObjects\CreateRoleData;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRoleAction extends Action
{
    /**
     * Create a new role.
     * 
     * @param CreateRoleData $data
     * @return void
     */
    public function handle(CreateRoleData $data): void
    {
        DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data->name,
            ]);

            $permissions = Permission::query()->whereIn('id', $data->permissions)->pluck('name')->toArray();
            
            $role->givePermissionTo($permissions);
        });
    }
}
