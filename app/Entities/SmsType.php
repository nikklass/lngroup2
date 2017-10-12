<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class SmsType extends Model
{
    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'id', 'name', 'description'
    ];
}
