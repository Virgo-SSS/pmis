<?php

namespace App\Http\Controllers;

use App\Actions\Departments\CreateDepartmentAction;
use App\Actions\Departments\DeleteDepartmentAction;
use App\Actions\Departments\UpdateDepartmentAction;
use App\DataTransferObjects\CreateDepartmentData;
use App\Http\Requests\Departments\DepartmentUpdateRequest;
use App\Http\Requests\Departments\DepartmentStoreRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::query()
            ->get();

        return view('departments.index', compact('departments'));
    }

    public function store(DepartmentStoreRequest $request, CreateDepartmentAction $action): RedirectResponse
    {
        $action->run(
            CreateDepartmentData::fromArray($request->validated())
        );
        
        return redirect()->route('department')->with('success-swal', 'Department created successfully');
    }

    public function update(DepartmentUpdateRequest $request, Department $department, UpdateDepartmentAction $action): RedirectResponse
    {
        $action->run(
            $department,
            CreateDepartmentData::fromArray($request->validated())
        );

        return redirect()->route('department')->with('success-swal', 'Department updated successfully');
    }

    public function delete(Department $department, DeleteDepartmentAction $action): RedirectResponse
    {
        $action->run($department);

        return redirect()->route('department')->with('success-swal', 'Department deleted successfully');
    }
}
