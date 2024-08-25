<?php

namespace App\Actions\Attendances;

use App\Actions\Action;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteAttendanceAction extends Action
{
    /**
     * Handle deleting an attendance.
     *
     * @param Attendance $attendance
     * @return void
     */
    public function handle(Attendance $attendance): void
    {
        DB::transaction(function () use ($attendance) {
            $attendance->delete();

            activity()
                ->causedBy(Auth::user())
                ->performedOn($attendance)
                ->withProperties([
                    'old' => [
                        'clock_in' => $attendance->clock_in->format('Y-m-d H:i:s'),
                        'clock_out' => $attendance->clock_out->format('Y-m-d H:i:s'),
                        'is_late' => $attendance->is_late ? 'Yes' : 'No',
                        'status' => $attendance->status->name,
                        'overtime' => $attendance->formatted_overtime,
                        'note' => $attendance->note,
                    ]
                ])
                ->event('deleted')
                ->log('Delete attendance for ' . $attendance->user->name);
        });
    }
}
