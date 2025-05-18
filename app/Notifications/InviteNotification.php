<?php

namespace App\Notifications;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class InviteNotification extends Notification
{
    use Notifiable;

    /**
     * Notification data
     */
    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $data = [
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'url' => $this->data['url'],
        ];

        if (isset($this->data['invite_id'])) {
            $data['invite_id'] = $this->data['invite_id'];
        }

        if (isset($this->data['election_id'])) {
            $data['election_id'] = $this->data['election_id'];
        }

        return $data;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->data;
    }
}
