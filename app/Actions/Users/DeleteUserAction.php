<?php

namespace App\Actions\Users;

use App\Actions\Action;
use App\Models\User;
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
        });
    }
}
