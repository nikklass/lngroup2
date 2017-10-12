<?php

namespace App\Transformers\Messages;

use App\Entities\Message;
use League\Fractal\TransformerAbstract;

/**
 * Class MessageTransformer.
 */
class MessageTransformer extends TransformerAbstract
{

    /**
     * @param Message $model
     * @return array
     */
    public function transform(Message $model)
    {

        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'subject' => $model->subject,
            'message' => $model->message,
            'status_id' => $model->status_id
        ];

    }

}
