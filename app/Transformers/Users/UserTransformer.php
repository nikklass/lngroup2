<?php

namespace App\Transformers\Users;

use App\Entities\City;
use App\Entities\Constituency;
use App\Entities\Country;
use App\Entities\State;
use App\Entities\User;
use App\Entities\Ward;
use App\Transformers\Cities\CityTransformer;
use App\Transformers\Countries\CountryTransformer;
use App\Transformers\States\StateTransformer;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class UserTransformer extends TransformerAbstract
{
    
    /**
     * @var array
     */
    //protected $defaultIncludes = ['country', 'state', 'city', 'roles'];
    protected $defaultIncludes = ['roles'];

    /**
     * @param User $model
     * @return array
     */
    public function transform(User $model)
    {
        
        if ($model->gender =='m') { $gender='Male'; } else { $gender='Female'; }

        $country = $this->getCountry($model->phone_country);
        if ($country) { $country_name = $country->name; } else { $country_name = null; }

        $state = $this->getState($model->state_id);
        if ($state) { $state_name = $state->name; } else { $state_name = null; }

        $city = $this->getCity($model->city_id);
        if ($city) { $city_name = $city->name; } else { $city_name = null; }

        $constituency = $this->getConstituency($model->constituency_id);
        if ($constituency) { 
            $constituency_name = $constituency->name; } else { $constituency_name = null; }

        $ward = $this->getWard($model->ward_id);
        if ($ward) { $ward_name = $ward->name; } else { $ward_name = null; }

        if ($model->dob) { $dob = $model->dob->toIso8601String(); } else { $dob = null; }

        return [
            'id' => $model->uuid,
            'user_id' => $model->id,
            'first_name' => $model->first_name,
            'last_name' => $model->last_name,
            'gender' => $model->gender,
            'gender_name' => $gender,
            'email' => $model->email,
            'phone' => $model->phone,
            'preferred_amount_fmt' => format_num($model->preferred_amount, 0),
            'preferred_amount' => $model->preferred_amount,
            'phone_country' => $model->phone_country,
            'phone_country_name' => $country_name,
            'state_id' => $model->state_id,
            'state' => $state_name,
            'city_id' => $model->city_id,
            'city' => $city_name,
            'constituency_id' => $model->constituency_id,
            'constituency' => $constituency_name,
            'ward_id' => $model->ward_id,
            'ward' => $ward_name,
            'dob_updated' => $model->dob_updated,
            'created_at' => $model->created_at->toIso8601String(),
            'dob' => $dob,
            'updated_at' => $model->updated_at->toIso8601String()
        ];

    }

    /**
     * @param User $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(User $model)
    {
        return $this->collection($model->roles, new RoleTransformer());
    }

    /**
     * @param get Country
     */
    public function getCountry($sortname)
    {
        if ($sortname) {
            return Country::where('sortname', $sortname)->first();
        } else {
            return null;
        }
    }

    public function getState($state_id)
    {
        if ($state_id) {
            return State::find($state_id);
        } else {
            return null;
        }
    }

    public function getCity($city_id)
    {
        if ($city_id) {
            return City::find($city_id);
        } else {
            return null;
        }
    }

    public function getConstituency($constituency_id)
    {
        if ($constituency_id) {
            return Constituency::find($constituency_id);
        } else {
            return null;
        }
    }

    public function getWard($ward_id)
    {
        if ($ward_id) {
            return Ward::find($ward_id);
        } else {
            return null;
        }
    }

}
