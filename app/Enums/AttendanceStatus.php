<?php

namespace App\Enums;

enum AttendanceStatus: int
{
    case ABSENT = 0;
    case PRESENT = 1;
    case LEAVE = 2;
    case PERMIT = 3;

    /**
     * Get instance from value
     *
     * @param int $value
     * @return self
     */
    public static function fromValue(int $value): self
    {
        return match ($value) {
            0 => self::ABSENT,
            1 => self::PRESENT,
            2 => self::LEAVE,
            3 => self::PERMIT,
        };
    }
}
