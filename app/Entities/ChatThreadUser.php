<?php

namespace App\Entities;

use App\Entities\ChatThread;
use App\Entities\Status;
use App\Entities\User;
use App\Entities\ChatMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ChatThreadUser.
 */
class ChatThreadUser extends Model
{
    
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'chat_thread_id', 'user_id', 'status_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function chatmessages() {
        return $this->belongsToMany(ChatMessage::class);
    }

    public function chatthread() {
        return $this->belongsTo(ChatThread::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
