<?php

namespace App\Services;

use Carbon\Carbon;

class AttendanceService
{
    public function normalizeOvertimeToSeconds(string $timeString): int
    {
        // Convert the time string to an array of hours, minutes, and seconds
        list($hours, $minutes, $seconds) = explode(':', $timeString);

        // Convert the time to seconds
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }
}
