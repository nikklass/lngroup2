<?php

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Chat.
 */
class Chat extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'sortname', 'phonecode', 'status_id', 'created_by', 'updated_by'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

}
