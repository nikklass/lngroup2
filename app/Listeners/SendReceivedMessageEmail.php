<?php

namespace App\Listeners;

use App\Events\MessageCreated;
use App\Jobs\SendReceivedMessageEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendReceivedMessageEmail
{
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageCreated  $event
     * @return void
     */
    public function handle(MessageCreated $event)
    {
        //send a new message to admin
        if ($event->message) {
                        
            //pass data to jobs queue
            dispatch(new SendReceivedMessageEmailJob($event->message));

        }
    }

}
