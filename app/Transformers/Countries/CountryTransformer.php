<?php

namespace App\Transformers\Countries;

use App\Entities\Country;
use League\Fractal\TransformerAbstract;

/**
 * Class CountryTransformer.
 */
class CountryTransformer extends TransformerAbstract
{

    /**
     * @param Country $model
     * @return array
     */
    public function transform(Country $model)
    {

        return [
            'id' => $model->id,
            'country_code' => $model->sortname,
            'name' => $model->name,
            'phone_code' => $model->phonecode,
            'created_by' => $model->created_by,
            'updated_by' => $model->updated_by,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];

    }

}
