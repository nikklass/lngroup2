<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Entities\User;

class NewUserConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $from_name = config('constants.email.from_name');
        $app_name = config('app.name');
        $first_name = $this->user->first_name;

        return $this->subject(ucfirst($first_name) . ', Welcome to ' . $app_name)
                        ->markdown('emails.user.newuserconfirmation');

    }
    
}
