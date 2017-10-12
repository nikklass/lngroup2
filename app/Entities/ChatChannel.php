<?php

namespace App\Entities;

use App\Entities\ChatThread;
use App\Entities\Status;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChatChannel.
 */
class ChatChannel extends Model
{
    
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'user_id', 'status_id', 'updated_by'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function chatthreads() {
        return $this->hasMany(ChatThread::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
