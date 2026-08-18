<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\SystemNotification;

class NotificationService
{
    public function toUser(User $user, string $title, string $message, string $type = 'info', ?string $url = null): void
    {
        $user->notify(new SystemNotification($title, $message, $type, $url));
    }

    public function toAdmins(string $title, string $message, string $type = 'info', ?string $url = null): void
    {
        User::admins()->get()->each(
            fn (User $admin) => $admin->notify(new SystemNotification($title, $message, $type, $url))
        );
    }

    public function toAllCustomers(string $title, string $message, string $type = 'info', ?string $url = null): void
    {
        User::customers()->chunk(200, function ($users) use ($title, $message, $type, $url) {
            foreach ($users as $user) {
                $user->notify(new SystemNotification($title, $message, $type, $url));
            }
        });
    }
}
