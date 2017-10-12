<?php

namespace App\Listeners;

use App\Entities\ConfirmCode;
use App\Events\UserCreated;
use App\Jobs\SendRegistrationSms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class SendConfirmationSms
{
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        //send an sms to the new user, if from kenya
        if ($event->user->phone_country == 'KE') {
            
            //formatted phone number
            $phone_number = getDatabasePhoneNumber($event->user->phone, $event->user->phone_country);

            //start disable any previous sent registration sms to this number
            $sms_type_id = config('constants.sms_types.registration_sms');
            $status_disabled = config('constants.status.disabled');
            $status_active = config('constants.status.active');

            DB::table('confirm_codes')
                            ->where('user_id', $event->user->id)
                            ->where('phone', $phone_number)
                            ->update(['status_id' => $status_disabled]);
            //end disable any previous sent registration sms to this number

            //start create new sms confirm code            
            $attributes['phone'] = $phone_number;
            $attributes['phone_country'] = $event->user->phone_country;
            $attributes['confirm_code'] = $event->user->confirm_code;
            $attributes['status_id'] = $status_active;
            $attributes['user_id'] = $event->user->id;

            ConfirmCode::create($attributes);
            //end create new sms confirm code

            //pass data to jobs queue
            dispatch(new SendRegistrationSms($event->user));

        }
    }
}
