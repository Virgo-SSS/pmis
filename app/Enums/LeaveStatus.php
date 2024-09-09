<?php

namespace App\Enums;

enum LeaveStatus: int
{
    case APPROVED = 1;
    case REJECTED = 2;
    case PENDING = 3;

    /**
     * Initialize the enum from an integer value.
     *
     * @param int $value
     * @return self
     */
    public static function init(int $value): self
    {
        return match ($value) {
            1 => self::APPROVED,
            2 => self::REJECTED,
            3 => self::PENDING,
        };
    }

    /**
     * Get the verb name of the enum.
     *
     * @return string
     */
    public function verb(): string
    {
        return match ($this) {
            self::APPROVED => 'Approve',
            self::REJECTED => 'Reject',
            self::PENDING => 'Pending',
        };
    }
}
