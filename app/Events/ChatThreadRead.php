<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Entities\ChatThreadTempJob;

class ChatThreadRead
{
    
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat_thread_temp;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChatThreadTempJob $chat_thread_temp)
    {
        $this->chat_thread_temp = $chat_thread_temp;
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
