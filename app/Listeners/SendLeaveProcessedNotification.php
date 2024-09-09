<?php

namespace App\Listeners;

use App\Events\LeaveProcessed;
use App\Notifications\LeaveProcessedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Queue\InteractsWithQueue;

class SendLeaveProcessedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param LeaveProcessed $event
     */
    public function handle(LeaveProcessed $event): void
    {
        $leave = $event->leave;
        $status = $event->status;

        $leave->user->notify(new LeaveProcessedNotification($leave, $status));
    }

    /**
     * Handle a job failure.
     *
     * @param LeaveProcessed $event
     * @param Throwable $exception
     * @return void
     */
    public function failed(LeaveProcessed $event, Throwable $exception): void
    {
        Log::channel('failed_jobs')->error('Failed to send leave processed notification', [
            'event' => 'leave.processed',
            'listener' => self::class,
            'leave' => $event->leave,
            'status' => $event->status,
            'exception' => $exception->getMessage(),
        ]);
    }
}
