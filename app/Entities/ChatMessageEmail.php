<?php

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChatMessageEmail.
 */
class ChatMessageEmail extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'sender_full_name', 'sender_message', 'thread_title', 'recipient_first_name', 'recipient_email'
    ];

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    /*public static function create(array $attributes = [])
    {

        $model = static::query()->create($attributes);

        return $model;

    }*/

}
