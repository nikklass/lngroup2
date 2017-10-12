<?php

namespace App\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;

use App\Entities\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ConfirmCode.
 */
class ConfirmCode extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 'email', 'phone', 'phone_country', 'confirm_code', 'status_id'
    ];

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {

        $model = static::query()->create($attributes);

        return $model;

    }

    /*one to many relationship*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
