<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MailSentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mail;
    public $message;    /**
     * Create a new event instance.
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
        $this->message = $mail->content; // Используйте поле с текстом письма, здесь я предполагаю, что оно называется "content"

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('mail-sent'),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'mail' => $this->mail,
            'message' => $this->message

        ];
    }
}
