<?php

namespace App\Http\Controllers;

use App\Actions\Attendances\CreateAttendanceAction;
use App\Actions\Attendances\DeleteAttendanceAction;
use App\Actions\Attendances\UpdateAttendanceAction;
use App\DataTransferObjects\CreateAttendanceData;
use App\Http\Requests\Attendances\AttendanceStoreRequest;
use App\Http\Requests\Attendances\AttendanceUpdateRequest;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $attendances = Attendance::query()->get();

        return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::query()->get();
        return view('attendances.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceStoreRequest $request, CreateAttendanceAction $action): RedirectResponse
    {
        $action->run(
            CreateAttendanceData::fromArray($request->validated())
        );

        return redirect()->route('attendance')->with('success-swal', 'Attendance created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance): View
    {
        $users = User::query()->get();
        return view('attendances.edit', compact('attendance', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttendanceUpdateRequest $request, Attendance $attendance, UpdateAttendanceAction $action): RedirectResponse
    {
        $action->run(
            $attendance,
            CreateAttendanceData::fromArray($request->validated())
        );

        return redirect()->route('attendance')->with('success-swal', 'Attendance updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Attendance $attendance, DeleteAttendanceAction $action): RedirectResponse
    {
        $action->run($attendance);

        return redirect()->route('attendance')->with('success-swal', 'Attendance deleted successfully!');
    }
}
