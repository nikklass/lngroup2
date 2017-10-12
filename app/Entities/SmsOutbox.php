<?php

namespace App\Entities;

use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Jenssegers\Agent\Agent;

class SmsOutbox extends Model
{
    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'message', 'phone', 'user_id', 'user_agent', 'src_ip', 'browser', 'browser_version', 'os', 'device', 'status_id', 'sms_type_id', 'schedule_sms_outbox_id', 'created_by', 'updated_by'
    ];

    /*one to many relationship*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*one to many relationship*/
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /*one to many relationship*/
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /*one to one relationship*/
    public function scheduleSmsOutbox()
    {
        return $this->belongsTo(ScheduleSmsOutbox::class);
    }


    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {

        //add user env
        $agent = new \Jenssegers\Agent\Agent;

        $attributes['user_agent'] = serialize($agent);
        $attributes['browser'] = $agent->browser();
        $attributes['browser_version'] = $agent->version($agent->browser());
        $attributes['os'] = $agent->platform();
        $attributes['device'] = $agent->device();
        $attributes['src_ip'] = getIp();
        //end add user env

        $model = static::query()->create($attributes);

        return $model;
    }


}
