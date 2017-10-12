<?php

namespace App\Entities;

use App\Entities\City;
use App\Entities\Country;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class State.
 */
class State extends Model
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

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function cities() {
        return $this->hasMany(City::class);
    }

}
