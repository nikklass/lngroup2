<?php

namespace App\Transformers\Users;

use App\Entities\OauthClient;
use League\Fractal\TransformerAbstract;

/**
 * Class OauthClientTransformer.
 */
class OauthClientTransformer extends TransformerAbstract
{
    
    public function transform(OauthClient $model)
    {
        
        return [
            
            'id' => $model->id,
            'user_id' => $model->user_id,
            'name' => $model->name,
            'email' => $model->email,
            'secret' => $model->secret,
            'redirect' => $model->redirect,
            'personal_access_client' => $model->personal_access_client,
            'password_client' => $model->password_client,
            'revoked' => $model->revoked,
            'updated_at' => $model->updated_at,
            'created_at' => $model->created_at

        ];

    }

}
