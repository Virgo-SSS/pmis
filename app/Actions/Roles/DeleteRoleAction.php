<?php

namespace App\Actions\Roles;

use App\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DeleteRoleAction extends Action
{
    /**
     * Delete a role.
     *
     * @param Role $role
     * @return void
     */
    public function handle(Role $role): void
    {
        DB::transaction(function () use ($role) {
            $originalRole = $role->getOriginal();
            $permissions = $role->permissions->pluck('name')->toArray();

            $role->delete();

            activity()
                ->causedBy(Auth::user())
                ->performedOn($role)
                ->event('deleted')
                ->withProperties([
                    'old' => [
                        'name' => $originalRole['name'],
                        'permissions' => implode(', ', $permissions),
                    ],
                ])
                ->log('Delete role with name ' . $role->name);
        });
    }
}
