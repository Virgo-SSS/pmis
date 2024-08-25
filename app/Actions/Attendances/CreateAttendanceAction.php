<?php

namespace App\Actions\Attendances;

use App\Actions\Action;
use App\DataTransferObjects\CreateAttendanceData;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateAttendanceAction extends Action
{
    /**
     * Handle creating a new attendance.
     *
     * @param CreateAttendanceData $data
     * @return void
     */
    public function handle(CreateAttendanceData $data): void
    {
        DB::transaction(function () use ($data) {
            $attendance = Attendance::query()
                ->create([
                    'user_id' => $data->user_id,
                    'clock_in' => $data->clock_in,
                    'clock_out' => $data->clock_out,
                    'note' => $data->note,
                    'status' => $data->status->value,
                    'is_late' => $data->is_late,
                    'overtime' => $data->overtime,
                ]);

            activity()
                ->causedBy(Auth::user())
                ->performedOn($attendance)
                ->withProperties([
                    'attributes' => [
                        'user' => $attendance->user->name,
                        'clock_in' => $attendance->clock_in->format('Y-m-d H:i:s'),
                        'clock_out' => $attendance->clock_out->format('Y-m-d H:i:s'),
                        'note' => $attendance->note,
                        'status' => $attendance->status->name,
                        'is_late' => $attendance->is_late ? 'Yes' : 'No',
                        'overtime' => $attendance->formatted_overtime,
                    ]
                ])
                ->event('created')
                ->log('create new attendance for ' . $attendance->user->name);
        });
    }
}
