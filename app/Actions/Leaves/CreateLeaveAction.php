<?php

namespace App\Actions\Leaves;

use App\Actions\Action;
use App\DataTransferObjects\CreateLeaveData;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateLeaveAction extends Action
{
    /**
     * Handle creating a new leave.
     *
     * @param CreateLeaveData $data
     * @return void
     */
    public function handle(CreateLeaveData $data): void
    {
        DB::transaction(function () use ($data) {
            $leave = Leave::query()->create([
                'user_id' => auth()->id(),
                'start_date' => $data->start_date,
                'end_date' => $data->end_date,
                'reason' => $data->reason,
            ]);

            activity()
                ->causedBy(Auth::user())
                ->performedOn($leave)
                ->withProperties([
                    'attributes' => [
                        'start_date' => $data->start_date->format('Y-m-d'),
                        'end_date' => $data->end_date->format('Y-m-d'),
                        'reason' => $data->reason,
                    ]
                ])
                ->event('created')
                ->log('Request a leave');
        });
    }
}
