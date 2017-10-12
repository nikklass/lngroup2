<?php

namespace App\Transformers\Wards;

use App\Entities\Ward;
use League\Fractal\TransformerAbstract;

/**
 * Class WardTransformer.
 */
class WardTransformer extends TransformerAbstract
{

    /**
     * @param Ward $model
     * @return array
     */
    public function transform(Ward $model)
    {

        return [
            'id' => $model->id,
            'name' => $model->name,
            'constituency_id' => $model->constituency_id,
            'created_by' => $model->created_by,
            'updated_by' => $model->updated_by,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];

    }

}
