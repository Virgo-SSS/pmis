<?php

namespace App\Http\Controllers;

use App\Actions\Roles\CreateRoleAction;
use App\Actions\Roles\DeleteRoleAction;
use App\Actions\Roles\UpdateRoleAction;
use App\DataTransferObjects\CreateRoleData;
use App\Http\Requests\Roles\RoleStoreRequest;
use App\Http\Requests\Roles\RoleUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()->with('permissions')->get();
        
        return view('roles.index', compact('roles'));
    }

    public function show(Role $role): View
    {
        $permissions = Permission::query()->get();
        
        return view('roles.show', compact('role', 'permissions'));
    }

    public function create(): View
    {
        $permissions = Permission::query()->get();
        
        return view('roles.create', compact('permissions'));
    }

    public function store(RoleStoreRequest $request, CreateRoleAction $action): RedirectResponse
    {
        $action->run(
            CreateRoleData::fromArray($request->validated())
        );

        return redirect()->route('role')->with('success-swal', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::query()->get();
        
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(RoleUpdateRequest $request, Role $role, UpdateRoleAction $action): RedirectResponse
    {
        $action->run(
            $role,
            CreateRoleData::fromArray($request->validated())
        );
        
        return redirect()->route('role')->with('success-swal', 'Role updated successfully.');
    }

    public function delete(Role $role, DeleteRoleAction $action): RedirectResponse
    {
        $action->run($role);
        
        return redirect()->route('role')->with('success-swal', 'Role deleted successfully.');
    }
}
