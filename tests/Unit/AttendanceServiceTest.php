<?php

namespace Tests\Unit;

use App\DataTransferObjects\CreateAttendanceData;
use App\Enums\AttendanceStatus;
use App\Services\AttendanceService;
use PHPUnit\Framework\TestCase;

    class AttendanceServiceTest extends TestCase
{
    /**
     * Test function normalizeOvertimeToSeconds returns the correct value
     *
     * @return void
     */
    public function test_normalizeOvertimeToSeconds_returns_correct_value(): void
    {
        $service = new AttendanceService();
        $time = '01:30:00';

        $this->assertEquals(5400, $service->normalizeOvertimeToSeconds($time));
    }
}
