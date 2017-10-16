<?php

namespace App\Http\Controllers\Api\Users;

use App\Entities\User;
use Dingo\Api\Exception\StoreResourceFailedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\DB;

class LoginProxy
{
    
    //use Helpers;

    const REFRESH_TOKEN = 'refreshToken';
    private $apiConsumer;
    private $model;
    private $auth;
    private $cookie;
    private $db;
    private $request;

    public function __construct(Application $app, User $model) {

        $this->model = $model;

        $this->apiConsumer = $app->make('apiconsumer');
        $this->auth = $app->make('auth');
        $this->cookie = $app->make('cookie');
        $this->db = $app->make('db');
        $this->request = $app->make('request');

    }

    /**
     * Attempt to create an access token using user credentials
     *
     * @param string $username
     * @param string $password
     */
    public function attemptLogin($username, $password)
    {

        $user = $this->model->where('email', $username)
                ->orWhere('phone', $username)
                ->first();

        //dd($user, $username, $password);

        if ($user) {
            
            //if user is not activated, show message
            if (!$user->active) {
                throw new StoreResourceFailedException("User account not activated. Please activate account to proceed.");
            }

            return $this->proxy('password', [
                'username' => $username,
                'password' => $password
            ]);

        }

        throw new StoreResourceFailedException("Invalid username or password");

    }

    /**
     * Attempt to refresh the access token used a refresh token that 
     * has been saved in a cookie
     */
    public function attemptRefresh($request)
    {
        //$refreshToken = $this->request->cookie(self::REFRESH_TOKEN);
        $refreshToken = $request->refresh_token;

        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    /**
     * Proxy a request to the OAuth server.
     * 
     * @param string $grantType what type of grant type should be proxied
     * @param array $data the data to send to the server
     */
    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [
            'client_id' => config('app.password_client_id'),
            'client_secret' => config('app.password_client_secret'),
            'grant_type'    => $grantType,
            'scope'    => ''
        ]);

        //dd($data);

        $response = $this->apiConsumer->post('/oauth/token', $data);

        if (!$response->isSuccessful()) {
            throw new StoreResourceFailedException("Wrong login credentials");
        }

        $data = json_decode($response->getContent());

        // Create a refresh token cookie
        $this->cookie->queue(
            self::REFRESH_TOKEN,
            $data->refresh_token,
            864000, // 10 days
            null,
            null,
            false,
            true // HttpOnly
        );

        return [
            'access_token' => $data->access_token,
            'refresh_token' => $data->refresh_token,
            'expires_in' => $data->expires_in
        ];
    }

    /**
     * Logs out the user. We revoke access token and refresh token. 
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        $accessToken = $this->auth->user()->token();

        $refreshToken = $this->db
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN));
    }
}
