<?php

namespace App\Actions\Banks;

use App\Actions\Action;
use App\DataTransferObjects\CreateBankData;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;

class CreateBankAction extends Action
{
    /**
     * Handle creating a new bank.
     *
     * @param CreateBankData $data
     * @return void
     */
    public function handle(CreateBankData $data): void
    {
        /**
         * Use transaction because we run multiple queries in this action
         * Log activity run behind the scene in the model
         */
        DB::transaction(function () use ($data) {
            $this->createBank($data->name);
        });
    }

    /**
     * Create a new Bank
     *
     * @param string $name
     * @return void
     */
    private function createBank(string $name): void
    {
        Bank::query()->create([
            'name' => $name,
        ]);
    }
}
