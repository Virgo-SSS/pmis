<?php

namespace App\Actions\Users;

use App\Actions\Action;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteUserAction extends Action
{
    /**
     * Handle deleting a user.
     *
     * @param User $user
     * @return void
     */
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->delete();

            $this->logActivity($user);
        });
    }

    /**
     * Log activity
     *
     * @param User $user
     * @return void
     */
    public function logActivity(User $user): void
    {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties([
                'old' => [
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                ]
            ])
            ->event('deleted')
            ->log('Delete user with name ' . $user->name);
    }
}
