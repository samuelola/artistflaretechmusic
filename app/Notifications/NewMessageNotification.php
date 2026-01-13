<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessageNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $title;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message)
    {
        $this->title   = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'user_id' => $notifiable->id,
        ]);
    }

    public function broadcastOn()
    {
        return ['App.Models.User.' . $this->notifiable->id];
    }

    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            
            'title'   => $this->title,
            'message' => $this->message,
            'icon'    => 'bitcoin-icons:verify-outline',
            'time'    => now()->diffForHumans(),
        ];
    }
}
