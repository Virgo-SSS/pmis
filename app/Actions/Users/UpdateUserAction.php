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
use Illuminate\Support\Facades\Storage;

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
            $original = $user->load( 'roles', 'profile.department', 'bank.bank')->toArray();

            $this->updateUser($user, $data);
            $this->updateProfile($user, $data);
            $this->updateUserBank($data, $user);
            $this->updateRoles($user, $data->roles);
            $this->logActivity($user, $original, $data);
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
        $profile_picture = $this->storeProfilePicture($data->profile_picture, $user);

        $user->profile()->update([
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
     * Update User Bank
     *
     * @param CreateUserData $data
     * @param User $user
     *
     * @return void
     */
    private function updateUserBank(CreateUserData $data, User $user): void
    {
        if(!$data->bank_id) {
            return;
        }

        $user->bank()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bank_id' => $data->bank_id,
                'account_name' => $data->bank_account_name,
                'account_number' => $data->bank_account_number,
            ]
        );
    }

    /**
     * Update user roles
     *
     */
    private function updateRoles(User $user, array $roles): void
    {
        $user->syncRoles($roles);
    }

    /**
     * Store profile picture
     *
     * @param File|UploadedFile|FileTesting|null $picture
     * @param User $user
     * @return string|null
     */
    private function storeProfilePicture(File|UploadedFile|FileTesting|null $picture, User $user): string|null
    {
        if(!$picture) {
            return $user->profile->profile_picture;
        }

        if ($user->profile->profile_picture) {
            Storage::disk('public')->delete('user-profile-pictures/' . $user->profile->profile_picture);
        }

        return app(FileService::class)->storeFile($picture, 'user-profile-pictures', isPublic: true);
    }

    /**
     * Log activity
     *
     * @param User $user
     * @param array $original
     * @return void
     */
    private function logActivity(User $user, array $original): void
    {
        $updated = $user->fresh()->load('roles', 'profile.department', 'bank.bank')->toArray();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties([
                'old' => [
                    'name' => $original['name'],
                    'username' => $original['username'],
                    'email' => $original['email'],
                    'department' => $original['profile']['department']['name'] ?? null,
                    'phone' => $original['profile']['phone'] ?? null,
                    'gender' => $original['profile']['gender'] ?? null,
                    'emergency contact' => $original['profile']['emergency_contact'] ?? null,
                    'address' => $original['profile']['address'] ?? null,
                    'joined_at' => $original['profile']['joined_at'] ?? null,
                    'bank' => $original['bank']['bank']['name'] ?? null,
                    'bank account number' => $original['bank']['account_number'] ?? null,
                    'bank account name' => $original['bank']['account_name'] ?? null,
                    'roles' => collect($original['roles'])->pluck('name')->implode(', ') ?? null,
                ],
                'attributes' => [
                    'name' => $updated['name'],
                    'username' => $updated['username'],
                    'email' => $updated['email'],
                    'department' => $updated['profile']['department']['name'] ?? null,
                    'phone' => $updated['profile']['phone'] ?? null,
                    'gender' => $updated['profile']['gender'] ?? null,
                    'emergency contact' => $updated['profile']['emergency_contact'] ?? null,
                    'address' => $updated['profile']['address'] ?? null,
                    'joined_at' => $updated['profile']['joined_at'] ?? null,
                    'bank' => $updated['bank']['bank']['name'] ?? null,
                    'bank account number' => $updated['bank']['account_number'] ?? null,
                    'bank account name' => $updated['bank']['account_name'] ?? null,
                    'roles' => collect($updated['roles'])->pluck('name')->implode(', ') ?? null,
                ]
            ])
            ->event('updated')
            ->log('Update user with name ' . $user->name);
    }
}
