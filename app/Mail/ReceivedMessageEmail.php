<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Entities\Message;

class ReceivedMessageEmail extends Mailable
{
    
    use Queueable, SerializesModels;

    public $message;

    /**
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $app_name = config('app.name');

        return $this->subject('You have a new message from ' . $app_name)
                        ->markdown('emails.receivedmessage.receivedmessageemail');

    }

}
