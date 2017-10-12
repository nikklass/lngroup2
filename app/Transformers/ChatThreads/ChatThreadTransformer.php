<?php

namespace App\Transformers\ChatThreads;

use App\Entities\ChatThread;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChatThreadTransformer.
 */
class ChatThreadTransformer extends TransformerAbstract
{

    /**
     * @param ChatThread $model
     * @return array
     */
    public function transform(ChatThread $model)
    {

        $creator = $model->user()->first();
        $updater = $model->updater()->first();
        $status = $model->status()->first();

        $created_by = $creator->first_name . " " . $creator->last_name;
        $updated_by = $updater->first_name . " " . $updater->last_name;

        //get number of messages in thread
        // $number_threads = DB::table('chat_messages')
        //                 ->selectRaw("count(id) as num")
        //                 ->where('chat_thread_id', $model->id)
        //                 ->first();

        $message_count = $model->chatmessages()->count();
        //dd($message_count);
        
        return [
            'id' => $model->id,
            'title' => $model->title,
            'chat_channel_id' => $model->chat_channel_id,
            'message_count' => $message_count,
            'status_id' => $model->status_id,
            'status' => $status->name,
            'creator_id' => $model->user_id,
            'updater_id' => $model->updated_by,
            'created_by' => $created_by,
            'updated_by' => $updated_by,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];

    }

}
