<?php

namespace App\Actions\Roles;

use App\Actions\Action;
use App\DataTransferObjects\CreateRoleData;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateRoleAction extends Action
{
    /**
     * Update a role.
     * 
     * @param Role $role
     * @param CreateRoleData $data
     * 
     * @return void
     */
    public function handle(Role $role, CreateRoleData $data): void
    {
        DB::transaction(function () use ($role, $data) {
            $role->update([
                'name' => $data->name,
            ]);
            
            $permissions = Permission::query()->whereIn('id', $data->permissions)->pluck('name')->toArray();
            
            $role->syncPermissions($permissions);    
        });
    }
}
