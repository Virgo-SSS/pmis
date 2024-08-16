<?php

namespace App\Actions\Roles;

use App\Actions\Action;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Permission\Models\Role;

class DeleteRoleAction extends Action
{
    public function handle(Role $role): void
    {
        $role->delete();
    }
}
