<?php

namespace App\Actions\Attendances;

use App\Actions\Action;
use App\DataTransferObjects\CreateAttendanceData;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateAttendanceAction extends Action
{
    /**
     * Handle updating an attendance.
     *
     * @param Attendance $attendance
     * @param CreateAttendanceData $data
     * @return void
     */
    public function handle(Attendance $attendance, CreateAttendanceData $data): void
    {
        DB::transaction(function () use ($attendance, $data) {
            $original = $attendance->replicate();

            $attendance->update([
                'clock_in' => $data->clock_in,
                'clock_out' => $data->clock_out,
                'is_late' => $data->is_late,
                'status' => $data->status,
                'overtime' => $data->overtime,
                'note' => $data->note,
            ]);

            activity()
                ->causedBy(Auth::user())
                ->performedOn($attendance)
                ->withProperties([
                    'old' => [
                        'clock_in' => $original->clock_in->format('Y-m-d H:i:s'),
                        'clock_out' => $original->clock_out->format('Y-m-d H:i:s'),
                        'is_late' => $original->is_late ? 'Yes' : 'No',
                        'status' => $original->status->name,
                        'overtime' => $original->formatted_overtime,
                        'note' => $original->note,
                    ],
                    'attributes' => [
                        'clock_in' => $attendance->clock_in->format('Y-m-d H:i:s'),
                        'clock_out' => $attendance->clock_out->format('Y-m-d H:i:s'),
                        'is_late' => $attendance->is_late ? 'Yes' : 'No',
                        'status' => $attendance->status->name,
                        'overtime' => $attendance->formatted_overtime,
                        'note' => $attendance->note,
                    ]
                ])
                ->event('updated')
                ->log('Update Attendance for ' . $attendance->user->name);
        });
    }
}
