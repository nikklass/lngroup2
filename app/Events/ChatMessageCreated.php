<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Entities\ChatMessageCreatedEmailTempJob;

class ChatMessageCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat_message_created_email_temp_job;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChatMessageCreatedEmailTempJob $chat_message_created_email_temp_job)
    {
        $this->chat_message_created_email_temp_job = $chat_message_created_email_temp_job;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
