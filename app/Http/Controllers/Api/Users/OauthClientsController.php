<?php

namespace App\Http\Controllers\Api\Users;

use App\Entities\OauthClient;
use App\Http\Controllers\Controller;
use App\Transformers\Users\OauthClientTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

/**
 * Class OauthClientsController.
 */
class OauthClientsController extends Controller
{
    
    use Helpers;

    /**
     * @var OauthClient
     */
    protected $model;

    /**
     * OauthClientsController constructor.
     *
     * @param OauthClient $model
     */
    public function __construct(OauthClient $model)
    {
        $this->model = $model;
    }


    public function index(Request $request)
    {
        $paginator = $this->model->paginate($request->get('limit', config('app.pagination_limit')));
        if ($request->has('limit')) {
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new OauthClientTransformer());
    }


    public function show($id)
    {
        $oauthClient = $this->model->findOrFail($id);

        return $this->response->item($oauthClient, new OauthClientTransformer());
    }


    public function destroy(Request $request, $id)
    {
        $oauthClient = $this->model->firstOrFail($id);
        $oauthClient->delete();

        return $this->response->noContent();
    }


}
