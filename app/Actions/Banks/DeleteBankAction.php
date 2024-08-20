<?php

namespace App\Actions\Banks;

use App\Actions\Action;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;

class DeleteBankAction extends Action
{
    /**
     * Handle deleting a bank.
     *
     * @param Bank $bank
     * @return void
     */
    public function handle(Bank $bank): void
    {
        /**
         * Use transaction because we run multiple queries in this action
         * Log activity run behind the scene in the model
         */
        DB::transaction(function () use ($bank) {
            $bank->delete();
        });
    }
}
