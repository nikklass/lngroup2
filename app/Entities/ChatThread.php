<?php

namespace App\Entities;

use App\Entities\ChatChannel;
use App\Entities\ChatMessage;
use App\Entities\ChatThreadUser;
use App\Entities\Status;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChatThread.
 */
class ChatThread extends Model
{
    
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'title', 'chat_channel_id', 'user_id', 'job_user_id', 'status_id', 'updated_by'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function chatthreadusers() {
        return $this->hasMany(ChatThreadUser::class, 'chat_thread_id', 'id');
    }

    public function chatchannel() {
        return $this->belongsTo(ChatChannel::class);
    }

    public function chatmessages() {
        return $this->hasMany(ChatMessage::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
