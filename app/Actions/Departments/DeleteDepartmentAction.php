<?php

namespace App\Actions\Departments;

use App\Actions\Action;
use App\Models\Department;

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
        $department->delete();
    }
}
