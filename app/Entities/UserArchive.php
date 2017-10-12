<?php

namespace App\Entities;

use App\Entities\Country;
use App\Support\HasRolesUuid;
use App\Support\UuidScopeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Carbon\Carbon;

/**
 * Class UserArchive.
 */
class UserArchive extends Authenticatable
{
    use Notifiable, UuidScopeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'first_name', 'last_name', 'email', 'uuid', 'password', 'gender', 'phone', 'preferred_amount', 'browser', 'browser_version', 'os', 'device', 'src_ip', 'user_agent', 'phone_country', 'state_id', 'city_id', 'constituency_id', 'ward_id', 'dob', 'confirm_code', 'active', 'status_id', 'deleted_at', 'created_by'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
    */
    protected $dates = ['dob'];

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {

        $user_id = null;
        if (auth()->user()) {
            $user_id = auth()->user()->id;
        }

        //add parent id
        $attributes['parent_id'] = $attributes['id'];

        //add created by
        $attributes['created_by'] = $user_id;

        //remove id field from array, since it will be auto populated (autoincrement field)
        unset($attributes['id']);
        unset($attributes['updated_by']);

        $model = static::query()->create($attributes);

        return $model;

    }

}
