<?php

namespace App\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;

use App\Events\MessageCreated;

use App\Entities\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message.
 */
class Message extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 'subject', 'message', 'status_id'
    ];

    /**
     * Fire events on the model, oncreated, onupdated
     */
    protected $events = [
        'created' => MessageCreated::class,
    ];

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {

        //user id
        $attributes['user_id'] = auth()->user()->id;

        $model = static::query()->create($attributes);

        return $model; 

    }

    /*one to many relationship*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
