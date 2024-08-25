<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'clock_in',
        'clock_out',
        'is_late',
        'status',
        'overtime',
        'note',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
            'is_late' => 'boolean',
            'status' => AttendanceStatus::class,
            'overtime' => 'integer',
        ];
    }

    /**
     * Relationship with User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relationship with User (Creator)
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    /**
     * Mutator for overtime attribute
     *
     * @return Attribute
     */
    public function overtime(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => app(AttendanceService::class)->normalizeOvertimeToSeconds($value)
        );
    }

    /**
     * Mutator get formatted overtime
     *
     * @return Attribute
     */
    public function formattedOvertime(): Attribute
    {
        return Attribute::make(
            get: fn() =>  Carbon::createFromTimestamp($this->overtime)->format('H:i:s')
        );
    }
}
