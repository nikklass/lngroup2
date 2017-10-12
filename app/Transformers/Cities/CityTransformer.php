<?php

namespace App\Transformers\Cities;

use App\Entities\City;
use League\Fractal\TransformerAbstract;

/**
 * Class CityTransformer.
 */
class CityTransformer extends TransformerAbstract
{

    /**
     * @param City $model
     * @return array
     */
    public function transform(City $model)
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
