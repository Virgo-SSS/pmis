<?php

namespace App\Actions\Users;

use App\Actions\Action;
use App\DataTransferObjects\CreateUserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUserAction extends Action
{
    /**
     * Handle updating a user.
     *
     * @param CreateUserData $data
     * @param User $user
     */
    public function handle(User $user, CreateUserData $data): void
    {
        DB::transaction(function () use ($data, $user) {
            $this->updateUser($user, $data);
            $this->updateProfile($user, $data);
            $this->updateRoles($user, $data->roles);
        });
    }

    /**
     * Update User
     *
     * @param User $user
     * @param CreateUserData $data
     *
     * @return void
     */
    public function updateUser(User $user, CreateUserData $data): void
    {
        $user->update([
            'name' => $data->name,
            'username' => $data->username,
            'email' => $data->email,
            'password' => $data->password ? bcrypt($data->password) : $user->password,
            'department_id' => $data->department_id,
            'profile_picture' => $data->profile_picture,
        ]);
    }

    /**
     * Update User Profile
     *
     * @param User $user
     * @param CreateUserData $data
     *
     * @return void
     */
    public function updateProfile(User $user, CreateUserData $data): void
    {
        $user->profile()->update([
            'department_id' => $data->department_id,
            'profile_picture' => $data->profile_picture,
            'phone' => $data->phone,
            'emergency_contact' => $data->emergency_contact,
            'address' => $data->address,
            'joined_at' => $data->joined_at->format('Y-m-d')
        ]);
    }

    /**
     * Update user roles
     *
     */
    private function updateRoles(User $user, array $roles): void
    {
        $user->syncRoles($roles);
    }
}
