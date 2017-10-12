<?php

namespace App\Listeners;

use App\Entities\ChatMessage;
use App\Entities\ChatMessageCreatedEmailTempJob;
use App\Entities\ChatMessageEmail;
use App\Entities\ChatThread;
use App\Entities\User;
use App\Events\ChatMessageCreated;
use App\Jobs\SendChatMessageCreatedEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;


class SendChatMessageCreatedEmail
{
    
    protected $model;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ChatMessageCreatedEmailTempJob $model)
    {
        $this->model = $model;
    }

    /**
     * Handle the event.
     *
     * @param  ChatMessageCreated  $event
     * @return void
     */
    public function handle(ChatMessageCreated $event)
    {
        
        //send an sms to thread users
        if ($event->chat_message_created_email_temp_job) {
            
            //pass data to jobs queue
            //dispatch(new SendChatMessageCreatedEmailJob($event->chat_message_created_email_temp_job));




            //get job details
            $user_id = $event->chat_message_created_email_temp_job->user_id;
            $message_id = $event->chat_message_created_email_temp_job->chat_message_id;

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
                        //Mail::to($email)->send(new NewChatMessageCreated($email_object));

                        //pass data to jobs queue
                        dispatch(new SendChatMessageCreatedEmailJob($email_object));
                    
                    }

                }

            }

            //delete temp job
            $event->chat_message_created_email_temp_job->delete();



        }

    }

}
