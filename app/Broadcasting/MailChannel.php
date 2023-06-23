<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Support\Facades\Notification;

class MailChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    public function broadcastWhen(User $user, Notification $notification)
    {
        // Укажите условие, когда события должны быть транслированы пользователю
        return $user->id === $notification->mail->to;
    }

    /**
     * Authenticate the user's access to the channel.
     */
//    public function join(User $user): array|bool
//    {
//        //
//    }
}
