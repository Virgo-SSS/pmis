<?php

namespace App\Actions\Users;

use App\Actions\Action;
use App\DataTransferObjects\CreateUserData;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\File;
use Illuminate\Http\Testing\File as FileTesting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
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
            $this->createUserBank($data, $user);
            $this->assignUserRoles($data->roles, $user);
            $this->logActivity($user);
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
        $profile_picture = $this->storeProfilePicture($data->profile_picture);

        $user->profile()
            ->create([
                'department_id' => $data->department_id,
                'profile_picture' => $profile_picture,
                'phone' => $data->phone,
                'gender' => $data->gender,
                'emergency_contact' => $data->emergency_contact,
                'address' => $data->address,
                'joined_at' => $data->joined_at->format('Y-m-d'),
            ]);
    }

    /**
     * Create a new user bank.
     *
     * @param CreateUserData $data
     * @param User $user
     * @return void
     */
    private function createUserBank(CreateUserData $data, User $user): void
    {
        if(!$data->bank_id) {
            return;
        }

        $user->bank()->create([
            'user_id' => $user->id,
            'bank_id' => $data->bank_id,
            'account_number' => $data->bank_account_number,
            'account_name' => $data->bank_account_name,
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

    /**
     * Store the profile picture.
     *
     * @param File|UploadedFile|FileTesting|null $picture
     * @return string|null
     */
    private function storeProfilePicture(File|UploadedFile|FileTesting|null $picture): string|null
    {
        if(!$picture) {
            return null;
        }

        return app(FileService::class)->storeFile($picture, 'user-profile-pictures', isPublic: true);
    }

    /**
     * Log the activity.
     *
     * @param User $user
     * @return void
     */
    private function logActivity(User $user): void
    {
        activity()
            ->performedOn($user)
            ->causedBy(Auth::user())
            ->withProperties([
                'attributes' => [
                    'department' => $user->profile->department->name,
                    'phone' => $user->profile->phone,
                    'emergency_contact' => $user->profile->emergency_contact,
                    'address' => $user->profile->address,
                    'joined_at' => $user->profile->joined_at->format('Y-m-d'),
                    'roles' => $user->roles->pluck('name')->toArray(),
                    'bank' => $user->bank->bank->name,
                    'bank account number' => $user->bank->account_number,
                    'bank account name' => $user->bank->account_name,
                ]
            ])
            ->event('created')
            ->log('Create new user with name ' . $user->name);
    }
}
