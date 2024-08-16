<?php

namespace App\Actions\Departments;

use App\Actions\Action;
use App\DataTransferObjects\CreateDepartmentData;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class UpdateDepartmentAction extends Action
{
    /**
     * Update a department.
     *
     * @param Department $department
     * @param CreateDepartmentData $data
     * @return void
     */
    public function handle(Department $department, CreateDepartmentData $data): void
    {
        /**
         * Use transaction because we run multiple queries in this action (update department and log the activity)
         * Log activity run behind the scene in the model
         */
        DB::transaction(function () use ($department, $data) {
            $department->update([
                'name' => $data->name,
            ]);
        });
    }
}
