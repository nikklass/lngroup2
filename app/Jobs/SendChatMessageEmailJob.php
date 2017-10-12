<?php

namespace App\Jobs;

use App\Entities\ChatMessageEmail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendChatMessageEmailJob implements ShouldQueue
{
    
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chat_messsage_email;

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
    public function __construct(ChatMessageEmail $chat_messsage_email)
    {
        $this->chat_messsage_email = $chat_messsage_email;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //send email message here
        $chat_message_created_email = new ChatMessage();
        
        $chat_message_created_email->sendChatMessageCreatedEmail($this->chat_messsage_email);
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
