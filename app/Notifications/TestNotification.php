<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TestNotification extends Notification
{
    use Queueable;

    public function __construct(public $message = "Hello from Pusher!") {}

    public function via($notifiable)
    {
        return ['broadcast', 'database']; 
        // 'broadcast' is required for real-time
        // 'database' is optional if you want to store it
    }

    public function toArray($notifiable)
    {
        return [
            'title'   => 'Test Notification',
            'message' => $this->message,
            'icon'    => 'bitcoin-icons:verify-outline',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
