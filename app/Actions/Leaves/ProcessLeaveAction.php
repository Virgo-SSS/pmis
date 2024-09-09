<?php

namespace App\Actions\Leaves;

use App\Actions\Action;
use App\Enums\LeaveStatus;
use App\Events\LeaveProcessed;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProcessLeaveAction extends Action
{
    /**
     * Handle processing a leave.
     *
     * @param Leave $leave
     * @param LeaveStatus $status
     * @return void
     */
    public function handle(Leave $leave, LeaveStatus $status): void
    {
        DB::transaction(function () use ($leave, $status) {
            $original = $leave;

            $this->process($leave, $status);
            $this->notifyUser($leave, $status);
            $this->logApproval($leave, $original, $status);
        });
    }

    /**
     * Process the leave.
     *
     * @param Leave $leave
     * @param LeaveStatus $status
     * @return void
     */
    public function process(Leave $leave, LeaveStatus $status): void
    {
        $leave->update([
            'status' => $status,
            'approved_by' => Auth::user()->id,
        ]);
    }

    /**
     * Dispatch event to notify user of leave status.
     *
     * @param Leave $leave
     * @param LeaveStatus $status
     * @return void
     */
    public function notifyUser(Leave $leave, LeaveStatus $status): void
    {
        event(new LeaveProcessed(
            leave: $leave,
            status: $status,
        ));
    }

    /**
     * Log the leave approval.
     *
     * @param Leave $leave
     * @param Leave $originalLeave
     * @param LeaveStatus $status
     * @return void
     */
    public function logApproval(Leave $leave, Leave $originalLeave, LeaveStatus $status): void
    {
        activity()
            ->performedOn($leave)
            ->causedBy(Auth::user())
            ->withProperties([
                'old' => [
                    'start_date' => $originalLeave->start_date->format('Y-m-d'),
                    'end_date' => $originalLeave->end_date->format('Y-m-d'),
                    'status' => $originalLeave->status->name,
                ],
                'attributes' => [
                    'start_date' => $leave->start_date->format('Y-m-d'),
                    'end_date' => $leave->end_date->format('Y-m-d'),
                    'status' => $leave->status->name,
                    'approved_by' => Auth::user()->name,
                ],
            ])
            ->event('leave.processed')
            ->log(sprintf('%s user %s leave request', $status->verb() , $leave->user->name));
    }
}
