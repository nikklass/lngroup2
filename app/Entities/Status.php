<?php

namespace App\Entities;

use App\Entities\ChatChannel;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'id', 'name', 'section'
    ];

    public function smsoutboxes()
    {
        return $this->hasMany(SmsOutbox::class);
    }

    public function schedulesmsoutboxes()
    {
        return $this->hasMany(ScheduleSmsOutbox::class);
    }

    public function chatchannels()
    {
        return $this->hasMany(ChatChannel::class);
    }

}
