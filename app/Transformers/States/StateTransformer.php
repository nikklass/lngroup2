<?php

namespace App\Transformers\States;

use App\Entities\State;
use League\Fractal\TransformerAbstract;

/**
 * Class StateTransformer.
 */
class StateTransformer extends TransformerAbstract
{

    /**
     * @param State $model
     * @return array
     */
    public function transform(State $model)
    {

        return [
            'id' => $model->id,
            'name' => $model->name,
            'country_id' => $model->country_id,
            'created_by' => $model->created_by,
            'updated_by' => $model->updated_by,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];

    }

}
