<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Bank extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'banks';

    protected $fillable = [
        'name',
    ];

    /**
     * Get the options for the bank activity log
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->setDescriptionForEvent(function (string $eventName) {
                switch ($eventName) {
                    case 'created':
                        return 'Create new bank with name ' . $this->name;
                        break;
                    case 'updated':
                        return 'Update bank from ' . $this->getOriginal('name') . ' to ' . $this->name;
                        break;
                    case 'deleted':
                        return 'Delete bank with name ' . $this->name;
                        break;
                }
            });
    }
}
