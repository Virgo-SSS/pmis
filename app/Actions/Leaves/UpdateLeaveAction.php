<?php

namespace App\Actions\Leaves;

use App\Actions\Action;
use App\DataTransferObjects\UpdateLeaveData;
use App\Enums\LeaveStatus;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\LeaveProcessed;

class UpdateLeaveAction extends Action
{
    /**
     * Handle updating a leave.
     *
     * @param Leave $leave
     * @param UpdateLeaveData $data
     * @return void
     */
    public function handle(Leave $leave, UpdateLeaveData $data): void
    {
        DB::transaction(function () use ($leave, $data) {
            $original = $leave->replicate();

            $this->update($leave, $data);
            $this->log($leave, $original);
            $this->notify($leave);
        });
    }

    /**
     * Update the leave.
     *
     * @param Leave $leave
     * @param UpdateLeaveData $data
     * @return void
     */
    public function update(Leave $leave, UpdateLeaveData $data): void
    {
        $leave->update([
            'start_date' => $data->start_date->format('Y-m-d'),
            'end_date' => $data->end_date->format('Y-m-d'),
            'reason' => $data->reason,
            'status' => $data->status,
            'approved_by' => $data->status == LeaveStatus::PENDING ? null : ($leave->approvedBy ?? Auth::id()),
        ]);
    }

    /**
     * Notify the user.
     *
     * @param Leave $leave
     * @return void
     */
    public function notify(Leave $leave): void
    {
        if($leave->status == LeaveStatus::PENDING) {
            return;
        }

        event(new LeaveProcessed($leave, $leave->status));
    }

    /**
     * Log the action.
     *
     * @param Leave $leave
     * @param Leave $original
     * @return void
     */
    public function log(Leave $leave, Leave $original): void
    {
        activity()
            ->performedOn($leave)
            ->causedBy(Auth::user())
            ->withProperties([
                'old' => [
                    'start_date' => $original->start_date->format('Y-m-d'),
                    'end_date' => $original->end_date->format('Y-m-d'),
                    'reason' => $original->reason,
                    'status' => $original->status->name,
                ],
                'attributes' => [
                    'start_date' => $leave->start_date->format('Y-m-d'),
                    'end_date' => $leave->end_date->format('Y-m-d'),
                    'reason' => $leave->reason,
                    'status' => $leave->status->name,
                ]
            ])
            ->event('updated')
            ->log('Update User ' . $leave->user->name . ' leave request');
    }
}
