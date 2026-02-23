<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Message $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $sender = $this->message->sender;
        $senderName = $sender?->name ?? 'Alguien';
        $preview = \Illuminate\Support\Str::limit($this->message->body, 60);
        $url = route('messages.index', [
            'user' => $sender?->id,
            'apartment' => $this->message->apartment_id,
        ]);

        return [
            'type' => 'new_message',
            'title' => 'Nuevo mensaje',
            'message' => "{$senderName}: {$preview}",
            'url' => $url,
            'message_id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
        ];
    }
}
