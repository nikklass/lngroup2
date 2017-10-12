<?php

namespace App\Transformers\ChatMessages;

use App\Entities\ChatMessage;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChatMessageTransformer.
 */
class ChatMessageTransformer extends TransformerAbstract
{

    /**
     * @param ChatMessage $model
     * @return array
     */
    public function transform(ChatMessage $model)
    {

        $creator = $model->user()->first();
        $updater = $model->updater()->first(); 
        $status = $model->status()->first();

        $created_by = $creator->first_name . " " . $creator->last_name;
        
        return [
            'id' => $model->id,
            'chat_text' => $model->chat_text,
            'status_id' => $model->status_id,
            'chat_thread_id' => $model->chat_thread_id,
            'status' => $status->name,
            'creator_id' => $model->user_id,
            'created_by' => $created_by,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];

    }

}
