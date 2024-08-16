<?php

namespace App\Actions\Departments;

use App\Actions\Action;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DeleteDepartmentAction extends Action
{
    /**
     * Delete a department.
     *
     * @param Department $department
     * @return void
     */
    public function handle(Department $department): void
    {
        /**
         * Use transaction because we run multiple queries in this action (delete department and log the activity)
         * Log activity run behind the scene in the model
         */
        DB::transaction(function () use ($department) {
            $department->delete();
        });
    }
}
