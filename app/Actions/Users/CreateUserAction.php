<?php

namespace App\Actions\Users;

use App\Actions\Action;
use App\DataTransferObjects\CreateUserData;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;

class CreateUserAction extends Action
{
    /**
     * Handle creating a new user.
     *
     * @param CreateUserData $data
     * @return void
     */
    public function handle(CreateUserData $data): void
    {
        DB::transaction(function () use ($data) {
            $user = $this->createUser($data);
            $this->createUserProfile($data, $user);
            $this->assignUserRoles($data->roles, $user);
        });
    }

    /**
     * Create a new user.
     *
     * @param CreateUserData $data
     * @return User
     */
    private function createUser(CreateUserData $data): User
    {
        return User::query()
            ->create([
                'name' => $data->name,
                'username' => $data->username,
                'email' => $data->email,
                'password' => bcrypt($data->password),
            ]);
    }

    /**
     * Create a new user profile.
     *
     * @param CreateUserData $data
     * @param User $user
     * @return void
     */
    private function createUserProfile(CreateUserData $data, User $user): void
    {
        UserProfile::query()
            ->create([
                'user_id' => $user->id,
                'department_id' => $data->department_id,
                'phone' => $data->phone,
                'emergency_contact' => $data->emergency_contact,
                'address' => $data->address,
                'joined_at' => $data->joined_at->format('Y-m-d'),
            ]);
    }

    /**
     * Assign roles to the user.
     *
     * @param array $roles
     * @param User $user
     * @return void
     */
    private function assignUserRoles(array $roles, User $user): void
    {
        $user->assignRole($roles);
    }
}
