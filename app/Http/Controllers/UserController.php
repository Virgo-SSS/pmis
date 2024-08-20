<?php

namespace App\Http\Controllers;

use App\Actions\Users\CreateUserAction;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\UpdateUserAction;
use App\DataTransferObjects\CreateUserData;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->get();

        return view('users.index', compact('users'));
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function create(): View
    {
        $departments = Department::query()->get();
        $roles = Role::query()->get();

        return view('users.create', compact('departments', 'roles'));
    }

    public function store(UserStoreRequest $request, CreateUserAction $action): RedirectResponse
    {
        $action->run(
            CreateUserData::fromArray($request->validated())
        );

        return redirect()->route('user')->with('success-swal', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $departments = Department::query()->get();
        $roles = Role::query()->get();

        return view('users.edit', compact('user', 'departments', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user, UpdateUserAction $action): RedirectResponse
    {
        $action->run(
            $user,
            CreateUserData::fromArray($request->validated())
        );

        return redirect()->route('user')->with('success-swal', 'User updated successfully.');
    }

    public function delete(User $user, DeleteUserAction $action): RedirectResponse
    {
        $action->run($user);

        return redirect()->route('user')->with('success-swal', 'User deleted successfully.');
    }
}
