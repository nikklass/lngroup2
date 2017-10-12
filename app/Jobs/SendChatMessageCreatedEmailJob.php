<?php

namespace App\Jobs;

use App\Entities\ChatMessageEmail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendChatMessageCreatedEmailJob implements ShouldQueue
{
    
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chat_message_email;

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
    public function __construct(ChatMessageEmail $chat_message_email)
    {
        $this->chat_message_email = $chat_message_email;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //set mail job
        if ($this->chat_message_email) {

            //get email data
            $email = $this->chat_message_email->email;

            //send user email
            Mail::to($email)->send(new NewChatMessageCreated($this->chat_message_email));

        }

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
