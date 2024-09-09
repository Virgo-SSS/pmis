<?php

namespace App\Actions\Leaves;

use App\Actions\Action;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteLeaveAction extends Action
{
    /**
     * Handling Delete a leave.
     *
     * @param Leave $leave
     * @return void
     */
    public function handle(Leave $leave): void
    {
        DB::transaction(function () use ($leave) {
            $leave->delete();

            activity()
                ->performedOn($leave)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old' => [
                        'start_date' => $leave->start_date->format('Y-m-d'),
                        'end_date' => $leave->end_date->format('Y-m-d'),
                        'reason' => $leave->reason,
                        'status' => $leave->status->name,
                    ],
                ])
                ->event('deleted')
                ->log('Delete User ' . $leave->user->name . ' leave request');
        });
    }
}
