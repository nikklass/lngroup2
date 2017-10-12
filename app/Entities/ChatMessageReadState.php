<?php

namespace App\Entities;

use App\Entities\ChatThread;
use App\Entities\ChatThreadTempJob;
use App\Entities\Status;
use App\Entities\User;
use App\Events\ChatThreadRead;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class ChatMessageReadState.
 */
class ChatMessageReadState extends Model
{
    
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'chat_message_id', 'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function chatmessage() {
        return $this->belongsTo(ChatMessage::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }


    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {

        //create chat message read state entry if none exists
        $thread_id = $attributes['chat_thread_id'];
                
        //start create temp job entry
        $user_id = auth()->user()->id;
        $temp_job_attributes['user_id'] = $user_id;
        $temp_job_attributes['chat_thread_id'] = $thread_id;

        $chat_message_temp_job = new ChatThreadTempJob();
        $temp_job = $chat_message_temp_job->create($temp_job_attributes);
        //end create temp job entry

        if ($temp_job) {
            //save the item to event
            event(new ChatThreadRead($temp_job));
        }

        return "Chat message read states created";

    }


    public function createMessageReadStates($chat_thread_temp_job) {

        //dump($chat_thread_temp_job);
        //get job details
        $chat_message_temp_job = ChatThreadTempJob::find($chat_thread_temp_job->id);
        $user_id = $chat_message_temp_job->user_id;
        $thread_id = $chat_message_temp_job->chat_thread_id;

        //get messages in thread
        $unread_thread_messages = DB::select("SELECT chat_messages.id as message_id, chat_messages.created_at, 
                                    chat_messages.chat_text,
                                    chat_messages.user_id,
                                    (SELECT chat_message_read_states.created_at 
                                        FROM chat_message_read_states 
                                        WHERE chat_message_read_states.chat_message_id = chat_messages.id 
                                        AND chat_message_read_states.user_id = $user_id) 
                                    AS ReadState
                                    FROM (chat_messages INNER JOIN users 
                                        ON chat_messages.user_id = users.id) 
                                    WHERE (
                                        ((chat_messages.chat_thread_id)=$thread_id)
                                    )
                                    ORDER BY chat_messages.created_at DESC");

        $new_chat_message_read_state = [];

        foreach($unread_thread_messages as $unread) {
            //loop thru and store each entry that has readstate==null in db
            if (!$unread->ReadState) {

                //create new read state entry
                $read_state_attributes['user_id'] = $user_id;
                $read_state_attributes['chat_message_id'] = $unread->message_id;

                //create item
                $new_chat_message_read_state[] = static::query()->create($read_state_attributes);

            }
        }
        //end

        //delete temp job
        $chat_message_temp_job->delete();

    }
    

}
