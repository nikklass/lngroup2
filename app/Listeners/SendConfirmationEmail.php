<?php

namespace App\Listeners;

use App\Entities\ConfirmCode;
use App\Events\UserCreated;
use App\Jobs\SendRegistrationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class SendConfirmationEmail
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        
        //send an sms to the new user
        if ($event->user) {
            
            //start disable any previous sent registration email to this user
            $status_disabled = config('constants.status.disabled');
            $status_active = config('constants.status.active');

            DB::table('confirm_codes')
                            ->where('user_id', $event->user->id)
                            ->where('email', $event->user->email)
                            ->update(['status_id' => $status_disabled]);
            //end disable any previous sent registration email to this user

            //start create new email confirm code
            $attributes['email'] = $event->user->email;
            $attributes['confirm_code'] = $event->user->confirm_code;
            $attributes['status_id'] = $status_active;
            $attributes['user_id'] = $event->user->id;

            ConfirmCode::create($attributes);
            //end create new email confirm code

            //pass data to jobs queue
            dispatch(new SendRegistrationEmail($event->user));

        }

    }

}
