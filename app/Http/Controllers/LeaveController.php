<?php

namespace App\Http\Controllers;

use App\Actions\Leaves\ProcessLeaveAction;
use App\Actions\Leaves\CreateLeaveAction;
use App\Actions\Leaves\DeleteLeaveAction;
use App\Actions\Leaves\UpdateLeaveAction;
use App\DataTransferObjects\CreateLeaveData;
use App\DataTransferObjects\UpdateLeaveData;
use App\Enums\LeaveStatus;
use App\Http\Requests\Leaves\LeaveStoreRequest;
use App\Http\Requests\Leaves\LeaveUpdateRequest;
use App\Models\Leave;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $leaves = Leave::query()->latest()->get();
        return view('leaves.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('leaves.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeaveStoreRequest $request
     * @param CreateLeaveAction $action
     *
     * @return RedirectResponse
     */
    public function store(LeaveStoreRequest $request, CreateLeaveAction $action): RedirectResponse
    {
        $action->run(
            CreateLeaveData::fromArray($request->validated())
        );

        return redirect()->route('leave')->with('success-swal', 'Leave created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Leave $leave
     * @return View
     */
    public function edit(Leave $leave): View
    {
        return view('leaves.edit', compact('leave'));
    }

    /**
     * Update the specified resource in storage.
     *
     *
     */
    public function update(LeaveUpdateRequest $request, Leave $leave, UpdateLeaveAction $action): RedirectResponse
    {
        $action->run(
            $leave,
            UpdateLeaveData::fromArray($request->validated())
        );

        return redirect()->route('leave')->with('success-swal', 'Leave updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Leave $leave
     * @param DeleteLeaveAction $action
     * @return RedirectResponse
     */
    public function delete(Leave $leave, DeleteLeaveAction $action): RedirectResponse
    {
        $action->run($leave);

        return redirect()->route('leave')->with('success-swal', 'Leave deleted successfully');
    }

    /**
     * Show Leave Review Page
     *
     * @return View
     */
    public function review(): View
    {
        $leaves = Leave::query()
            ->with(['user', 'approvedBy'])
            ->where('status', LeaveStatus::PENDING)->latest()->get();

        return view('leaves.review', compact('leaves'));
    }

    /**
     * Approve the leave.
     *
     * @param Leave $leave
     * @param ProcessLeaveAction $action
     * @return RedirectResponse
     */
    public function approve(Leave $leave, ProcessLeaveAction $action): RedirectResponse
    {
        $action->run($leave, LeaveStatus::APPROVED);

        return redirect()->route('leave')->with('success-swal', 'Leave approved successfully');
    }

    /**
     * Reject the leave.
     *
     * @param Leave $leave
     * @param ProcessLeaveAction $action
     * @return RedirectResponse
     */
    public function reject(Leave $leave, ProcessLeaveAction $action): RedirectResponse
    {
        $action->run($leave, LeaveStatus::REJECTED);

        return redirect()->route('leave')->with('success-swal', 'Leave rejected successfully');
    }

}
