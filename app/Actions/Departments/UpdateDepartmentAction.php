<?php

namespace App\Actions\Departments;

use App\Actions\Action;
use App\DataTransferObjects\CreateDepartmentData;
use App\Models\Department;

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
        $department->update([
            'name' => $data->name,
        ]);
    }
}
