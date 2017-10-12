<?php

namespace App\Transformers\ChatChannels;

use App\Entities\ChatChannel;
use App\Entities\ChatThread;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use League\Fractal\TransformerAbstract;

/**
 * Class ChatChannelTransformer.
 */
class ChatChannelTransformer extends TransformerAbstract
{

    /**
     * @param ChatChannel $model
     * @return array
     */
    public function transform(ChatChannel $model)
    {

        $creator = $model->user()->first();
        $updater = $model->updater()->first();
        $status = $model->status()->first();

        $created_by = $creator->first_name . " " . $creator->last_name;
        $updated_by = $updater->first_name . " " . $updater->last_name;

        //get number of threads in channel
        /*$number_threads = DB::table('chat_threads')
                        ->selectRaw("count(id) as num")
                        ->where('chat_channel_id', $model->id)
                        ->first();*/

        $thread_count = $model->chatthreads()->count();
        
        return [
            'id' => $model->id,
            'name' => $model->name,
            'status_id' => $model->status_id,
            'status' => $status->name,
            'thread_count' => $thread_count,
            'creator_id' => $model->user_id,
            'updater_id' => $model->updated_by,
            'created_by' => $created_by,
            'updated_by' => $updated_by,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];

    }

}
