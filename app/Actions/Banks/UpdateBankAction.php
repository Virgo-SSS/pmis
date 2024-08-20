<?php

namespace App\Actions\Banks;

use App\Actions\Action;
use App\DataTransferObjects\CreateBankData;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;

class UpdateBankAction extends Action
{
    /**
     * Handle updating a bank.
     *
     * @param Bank $bank
     * @param CreateBankData $data
     * @return void
     */
    public function handle(Bank $bank, CreateBankData $data): void
    {
        /**
         * Use transaction because we run multiple queries in this action
         * Log activity run behind the scene in the model
         */
        DB::transaction(function () use ($data, $bank) {
            $bank->update([
                'name' => $data->name,
            ]);
        });
    }
}
