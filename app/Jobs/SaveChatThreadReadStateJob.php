<?php

namespace App\Jobs;

use App\Entities\ChatMessageReadState;
use App\Entities\ChatThreadTempJob;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveChatThreadReadStateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chat_thread_temp;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ChatThreadTempJob $chat_thread_temp)
    {
        $this->chat_thread_temp = $chat_thread_temp;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $chat_message_read_state = new ChatMessageReadState();
        
        $chat_message_read_state->createMessageReadStates($this->chat_thread_temp);
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
    */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc... send email to who?
    }

}
