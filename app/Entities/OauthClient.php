<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OauthClient.
 */
class OauthClient extends Model
{

    protected $fillable = [
        'id', 'user_id', 'name', 'secret', 'redirect', 'personal_access_client', 'password_client', 'revoked', 'created_at', 'updated_at'
    ];

}
