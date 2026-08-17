<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    public function __construct(
        protected string $title,
        protected string $message,
        protected string $type = 'info',
        protected ?string $url = null,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'type'    => $this->type,
            'url'     => $this->url,
        ];
    }
}
