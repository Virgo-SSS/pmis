<?php

namespace App\DataTransferObjects;

use App\Enums\LeaveStatus;
use Carbon\Carbon;

class UpdateLeaveData
{
    public function __construct(
        public readonly Carbon $start_date,
        public readonly Carbon $end_date,
        public readonly ?string $reason,
        public readonly LeaveStatus $status,
    ){}

    /**
     * Create a new instance from an array.
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            start_date: Carbon::parse($data['start_date']),
            end_date: Carbon::parse($data['end_date']),
            reason: $data['reason'] ?? null,
            status: LeaveStatus::init($data['status']),
        );
    }
}
