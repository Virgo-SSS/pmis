<?php

namespace App\Actions\Departments;

use App\Actions\Action;
use App\DataTransferObjects\CreateDepartmentData;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class CreateDepartmentAction extends Action
{
    /**
     * Create a new department.
     *
     * @param CreateDepartmentData $data
     * @return void
     */
    public function handle(CreateDepartmentData $data): void
    {
        DB::transaction(function () use ($data) {
            Department::query()
                ->create([
                    'name' => $data->name,
                ]);
        });
    }
}
