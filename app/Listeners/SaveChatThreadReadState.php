<?php

namespace App\Listeners;

use App\Entities\ChatThreadTempJob;
use App\Events\ChatThreadRead;
use App\Jobs\SaveChatThreadReadStateJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class SaveChatThreadReadState
{
    
    protected $model;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ChatThreadTempJob $model)
    {
        $this->model = $model;
    }

    /**
     * Handle the event.
     *
     * @param  ChatThreadRead  $event
     * @return void
     */
    public function handle(ChatThreadRead $event)
    {
        
        //store chat read states data
        if ($event->chat_thread_temp) {
            
            //pass data to jobs queue
            dispatch(new SaveChatThreadReadStateJob($event->chat_thread_temp));

        }

    }

}
