<?php

namespace App\Http\Controllers\Api\Users;

use Hash;
use DateTime;
use App\Entities\User;
use App\Http\Controllers\Api\Users\LoginProxy;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;

use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

/**
 * Class LoginController
 */
class LoginController extends Controller
{

    use Helpers;

    protected $server;
    
    protected $tokens;

    private $loginProxy;

    /**
     * LoginController constructor    
    */
    public function __construct(LoginProxy $loginProxy, ResourceServer $server, TokenRepository $tokens) {
        $this->loginProxy = $loginProxy;
        $this->server = $server;
        $this->tokens = $tokens;
    }

    public function login(LoginRequest $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        return $this->response->array($this->loginProxy->attemptLogin($username, $password));
    }

    public function refresh(Request $request)
    {
        return $this->response->array($this->loginProxy->attemptRefresh($request));
    }

    public function logout()
    {
        $this->loginProxy->logout();

        return $this->response(null, 204);
    }


    public function validateToken(Request $request, $localCall = false) {

        $psr = (new DiactorosFactory)->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);

            $token = $this->tokens->find(
                $psr->getAttribute('oauth_access_token_id')
            );

            $currentDate = new DateTime();
            $tokenExpireDate = new DateTime($token->expires_at);

            $isAuthenticated = $tokenExpireDate > $currentDate ? true : false;

            if($localCall) {
                return $isAuthenticated;
            }
            else {
                return json_encode(array('authenticated' => $isAuthenticated));
            }
        } catch (OAuthServerException $e) {
            if($localCall) {
                return false;
            }
            else {
                return json_encode(array('error' => 'Something went wrong with authenticating. Please logout and login again.'));
            }
        }
    }
        
}
