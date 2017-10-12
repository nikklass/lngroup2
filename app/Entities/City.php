<?php

namespace App\Entities;

use App\Entities\State;
use App\Entities\User;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User.
 */
class City extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'state_id', 'created_by', 'updated_by'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function state() {
        return $this->belongsTo(State::class);
    }

}
