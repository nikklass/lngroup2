<?php

namespace App\Transformers\Constituencies;

use App\Entities\Constituency;
use League\Fractal\TransformerAbstract;

/**
 * Class ConstituencyTransformer.
 */
class ConstituencyTransformer extends TransformerAbstract
{

    /**
     * @param Constituency $model
     * @return array
     */
    public function transform(Constituency $model)
    {

        return [
            'id' => $model->id,
            'name' => $model->name,
            'state_id' => $model->state_id,
            'created_by' => $model->created_by,
            'updated_by' => $model->updated_by,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];

    }

}
