<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Entities\ChatMessageEmail;

class NewChatMessageCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $chat_message_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ChatMessageEmail $chat_message_email)
    {
        $this->chat_message_email = $chat_message_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $app_name = config('app.name');
        $first_name = $this->chat_message_email->recipient_first_name;

        dump('Sending email to ' . $first_name . ' ...');

        $emaildata = $this->subject(ucfirst($first_name) . ', you have a new message from ' . $app_name)
                        ->markdown('emails.chat.newchatmessageemail');

        //delete email object after use
        $this->chat_message_email->delete();

        return $emaildata;

    }

}
