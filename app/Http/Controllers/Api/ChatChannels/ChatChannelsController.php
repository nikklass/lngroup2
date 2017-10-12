<?php

namespace App\Http\Controllers\Api\ChatChannels;

use App\Entities\ChatChannel;
use App\Http\Controllers\Controller;
use App\Transformers\ChatChannels\ChatChannelTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class ChatChannelsController.
 */
class ChatChannelsController extends Controller
{
    use Helpers;

    /**
     * @var ChatChannel
     */
    protected $model;


    /**
     * ChatsController constructor.
     *
     * @param ChatChannel $model
     */
    public function __construct(ChatChannel $model)
    {
        $this->model = $model;
        //$this->middleware('permission:List chatchannels')->only('index');
        //$this->middleware('permission:Create chatchannels')->only('store');
        //$this->middleware('permission:List chatchannels')->only('show');
        $this->middleware('permission:Update chatchannels')->only('update');
        $this->middleware('permission:Delete chatchannels')->only('destroy');
    }


    /**
     * Returns the ChatChannels resource 
     *
     * @param Request $request
     * @return mixed
     */

    /*$articles = Cache::remember('articles', 22*60, function() {
        return Article::all();
    });*/

    public function index(Request $request)
    {
        
        /*start cache settings*/
        $url = request()->url();
        $params = request()->query();

        $fullUrl = getFullCacheUrl($url, $params);
        $minutes = getCacheDuration('low'); 
        /*end cache settings*/


        $status_id = $request->status_id;
        
        if (!$status_id) { $status_id = 1; }

        $paginator = $this->model->where('status_id', $status_id)
                     ->orderBy('name', 'asc')
                     ->paginate($request->get('limit', config('app.pagination_limit')));

        $data = $this->response->paginator($paginator, new ChatChannelTransformer());

        //return cached data or cache if cached data not exists
        return Cache::remember($fullUrl, $minutes, function () use ($data) {
            return $data;
        });

    }


    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {

        /*start cache settings*/
        $url = request()->url();
        $params = request()->query();

        $fullUrl = getFullCacheUrl($url, $params);
        $minutes = getCacheDuration(); 
        /*end cache settings*/


        $chat_channel = $this->model->findOrFail($id);

        $data = $this->response->item($chat_channel, new ChatChannelTransformer());

        //return cached data or cache if cached data not exists
        return Cache::remember($fullUrl, $minutes, function () use ($data) {
            return $data;
        });

    }



    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {

        $user_id = auth()->user()->id;

        request()->merge(array(
                    'user_id' => $user_id,
                    'updated_by' => $user_id
                ));

        $rules = [
            'name' => 'required|unique:chat_channels'
        ];

        $payload = app('request')->only('name');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        //create channel
        $chat_channel = $this->model->create($request->all());

        return ['message' => 'Chat Channel created'];

    }


    /**
     * @param Request $request
     * @param $uuid
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        
        $chat_channel = $this->model->findOrFail($id);
        $rules = [
            'name' => 'required',
        ];
        if ($request->method() == 'PATCH') {
            $rules = [
                'name' => 'sometimes|required',
            ];
        }

        $payload = app('request')->only('name');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        $chat_channel->update($request->only('name', 'updated_by'));

        return $this->response->item($chat_channel->fresh(), new ChatChannelTransformer());

    }


    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        $chat_channel = $this->model->findOrFail($id);
        $chat_channel->delete();

        return $this->response->noContent();
    }
}
