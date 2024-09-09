<?php

namespace App\Notifications;

use App\Enums\LeaveStatus;
use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Leave $leave,
        public LeaveStatus $status,
    )
    {
        $this->onConnection('redis');
        $this->onQueue('notification');
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Leave Request Has Been ' . $this->status->name)
            ->view(
                'emails.leave-processed',
                [
                    'leave' => $this->leave,
                    'status' => $this->status,
                ]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Your leave request with dates ' . $this->leave->start_date->format('Y-m-d') . ' to ' . $this->leave->end_date->format('Y-m-d') . ' has been ' . $this->status->name,
        ];
    }
}
