<?php

namespace App\Jobs;

use App\Entities\SmsOutbox;
use App\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRegistrationSms implements ShouldQueue
{
    
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

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
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        //get sms data
        $phone = $this->user->phone;
        $phone_country = $this->user->phone_country;
        $sms_data = getBulkSMSData(config('constants.bulk_sms.usr'));
        $sms_data = $sms_data->data;
        $attributes['usr'] = $sms_data->username;
        $attributes['pass'] = $sms_data->passwd;
        $attributes['src'] = $sms_data->default_source;
        $attributes['message'] = $this->user->confirm_code;

        //add formatted phone number
        $phone_number = getDatabasePhoneNumber($phone, $phone_country);
        $attributes['phone'] = $phone_number;

        //send sms
        $send_sms_data = sendsms($attributes);

        //create sms outbox
        $sms_type_id = config('constants.sms_types.registration_sms');
        $attributes['sms_type_id'] = $sms_type_id;
        SmsOutbox::create($attributes);

    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
        //remove the created confirm code from db
    }

}
