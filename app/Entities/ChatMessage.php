<?php

namespace App\Entities;

use App\Entities\ChatChannel;
use App\Entities\ChatMessage;
use App\Entities\ChatMessageCreatedEmailTempJob;
use App\Entities\ChatMessageEmail;
use App\Entities\ChatThreadUser;
use App\Entities\Status;
use App\Entities\User;
use App\Events\ChatMessageCreated;
use App\Mail\NewChatMessageCreated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail;

/**
 * Class ChatMessage.
 */
class ChatMessage extends Model
{
    
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'chat_text', 'chat_thread_id', 'user_id', 'status_id', 'updated_by'
    ];

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {

        //create thread users entry if none exists
        $thread_id = $attributes['chat_thread_id'];
        $user = auth()->user();
        $user_id = $user->id;
        
        //check if thread user exists
        $thread_user = ChatThreadUser::where('user_id', $user_id)
                        ->where('chat_thread_id', $thread_id)
                        ->first();

        if (!$thread_user) {
            
            //create new thread user/ participant
            $thread_attributes['user_id'] = $user_id;
            $thread_attributes['chat_thread_id'] = $thread_id;

            $thread_user = ChatThreadUser::create($thread_attributes);
            
        }
        
        //create item
        $model = static::query()->create($attributes);

        /*if ($model) {
            //start execute chat message created event
            $message_id = $model->id;
            $user_id = auth()->user()->id;
            //start create temp job entry
            $temp_job_attributes['user_id'] = $user_id;
            $temp_job_attributes['chat_message_id'] = $message_id;

            $chat_message_created_email = new ChatMessageCreatedEmailTempJob();
            $temp_job = $chat_message_created_email->create($temp_job_attributes);
            //end create temp job entry

            if ($temp_job) {
                //fire chat message created event
                event(new ChatMessageCreated($temp_job));
            }
            //end execute chat message created event
        }*/

        return $model;

    }


    public function sendChatMessageCreatedEmail($chat_message_created_email_temp_job) {

        //get job details
        $user_id = $chat_message_created_email_temp_job->user_id;
        $message_id = $chat_message_created_email_temp_job->chat_message_id;

        ////////////////////////
        if ($user_id && $message_id) {
            
            //get chat message
            $chat_message = ChatMessage::find($message_id);

            $chat_text = $chat_message->chat_text;
            $user_id = $chat_message->user_id;
            $chat_thread_id = $chat_message->chat_thread_id;
            $chat_created_at = $chat_message->created_at;

            //get chat user
            $chat_user = User::find($user_id);

            $first_name = $chat_user->first_name;
            $last_name = $chat_user->last_name;
            $full_name = $first_name . ' ' . $last_name;

            //get chat thread
            $chat_thread = ChatThread::find($chat_thread_id);
            $thread_title = $chat_thread->title;

            //get users participating in thread
            $chat_thread_users = $chat_thread->chatthreadusers()
                                ->where('user_id', '!=', $user_id)
                                ->get();

            foreach ($chat_thread_users as $chat_thread_user) {
                
                $user_id = $chat_thread_user->user_id;
                $user = User::find($user_id);
                $email = $user->email;
                $first_name = $user->first_name;

                //delete all used email objects
                ChatMessageEmail::where('status_id', '1')->delete();

                $email_object = ChatMessageEmail::create([
                    'thread_title' => $thread_title,
                    'sender_full_name' => $full_name,
                    'sender_message' => $chat_text,
                    'recipient_first_name' => $first_name,
                    'recipient_email' => $email                        
                ]);
                
                //dump($email_object);

                if ($email_object) {

                    //dump($email, $first_name, $thread_title, $full_name, $chat_text);

                    //send user email
                    Mail::to($email)->send(new NewChatMessageCreated($email_object));
                
                }

            }

        }

        //delete temp job
        $chat_message_created_email_temp_job->delete();

    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function chatchannel() {
        return $this->belongsTo(ChatChannel::class);
    }

    public function chatmessage() {
        return $this->belongsTo(ChatMessage::class);
    }

    public function chatthreadusers() {
        return $this->belongsToMany(ChatThreadUser::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
