<?php

namespace EventListener;

use App\Enums\LeaveStatus;
use App\Events\LeaveProcessed;
use App\Listeners\SendLeaveProcessedNotification;
use App\Models\Leave;
use App\Notifications\LeaveProcessedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendLeaveProcessedNotificationTest extends TestCase
{
    /**
     * Test leave processed notification is attached to event.
     *
     * @return void
     */
    public function test_listener_is_attached_to_event(): void
    {
        Event::fake();

        Event::assertListening(
            LeaveProcessed::class,
            SendLeaveProcessedNotification::class,
        );
    }

    /**
     * Test leave processed notification is sent to user.
     *
     * @return void
     */
    public function test_notification_is_sent_to_user(): void
    {
        Notification::fake();

        $leave = Leave::factory()->create();
        $status = LeaveStatus::APPROVED;

        $event = new LeaveProcessed(
            leave: $leave,
            status: $status,
        );
        $listener = new SendLeaveProcessedNotification();

        $listener->handle($event);

        Notification::assertSentTo(
            $leave->user,
            LeaveProcessedNotification::class,
        );

        Notification::assertSentTo(
            $leave->user,
            LeaveProcessedNotification::class,
            function (LeaveProcessedNotification $notification) use ($leave, $status) {
                return $notification->leave->is($leave)
                    && $notification->status === $status;
            },
        );

        Notification::assertSentToTimes(
            $leave->user,
            LeaveProcessedNotification::class,
            1,
        );
    }
}
