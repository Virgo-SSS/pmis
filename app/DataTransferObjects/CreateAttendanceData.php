<?php

namespace App\DataTransferObjects;

use App\Enums\AttendanceStatus;
use Carbon\Carbon;

class CreateAttendanceData
{
    public function __construct(
        public readonly ?int $user_id,
        public readonly Carbon $clock_in,
        public readonly Carbon $clock_out,
        public readonly ?string $note,
        public readonly AttendanceStatus $status,
        public readonly bool $is_late,
        public readonly string $overtime,
    ){}

    /**
     * Create a new instance from the given array.
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'] ?? null,
            clock_in: isset($data['clock_in']) ? Carbon::parse($data['clock_in']) : null,
            clock_out: isset($data['clock_out']) ? Carbon::parse($data['clock_out']) : null,
            note: $data['note'] ?? null,
            status: AttendanceStatus::fromValue($data['status']),
            is_late: $data['is_late'],
            overtime: $data['overtime'],
        );
    }
}
