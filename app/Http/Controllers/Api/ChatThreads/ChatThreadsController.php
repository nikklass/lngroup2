<?php

namespace App\Http\Controllers\Api\ChatThreads;

use App\Entities\ChatThread;
use App\Http\Controllers\Controller;
use App\Transformers\ChatThreads\ChatThreadTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ChatThreadsController.
 */
class ChatThreadsController extends Controller
{
    use Helpers;

    /**
     * @var ChatThread
     */
    protected $model;

    /**
     * ChatsController constructor.
     *
     * @param ChatThread $model
     */
    public function __construct(ChatThread $model)
    {
        $this->model = $model;
        /*$this->middleware('permission:List chatchannels')->only('index');
        $this->middleware('permission:Create chatchannels')->only('store');
        $this->middleware('permission:List chatchannels')->only('show');
        $this->middleware('permission:Update chatchannels')->only('update');
        $this->middleware('permission:Delete chatchannels')->only('destroy');*/
    }


    /**
     * Returns the ChatThreads resource.
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        
        /*start cache settings*/
        $url = request()->url();
        $params = request()->query();

        $fullUrl = getFullCacheUrl($url, $params);
        $minutes = getCacheDuration(); 
        /*end cache settings*/


        $status_id = $request->status_id;
        
        if (!$status_id) { $status_id = 1; }

        $chat_threads = $this->model;

        if ($request->chat_channel_id) {
            $chat_threads = $chat_threads->where('chat_channel_id', $request->chat_channel_id);
        }

        $paginator = $chat_threads->where('status_id', $status_id)
                     ->orderBy('id', 'desc')
                     ->paginate($request->get('limit', config('app.pagination_limit')));

        $data = $this->response->paginator($paginator, new ChatThreadTransformer());

        //return cached data or cache if cached data not exists
        return cache()->remember($fullUrl, $minutes, function () use ($data) {
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

        $chat_thread = $this->model->findOrFail($id);

        $data = $this->response->item($chat_thread, new ChatThreadTransformer());

        //return cached data or cache if cached data not exists
        return cache()->remember($fullUrl, $minutes, function () use ($data) {
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
            'title' => 'required|unique:chat_threads',
            'chat_channel_id' => 'required'
        ];

        $payload = app('request')->only('title', 'chat_channel_id');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        //create channel
        $chat_channel = $this->model->create($request->all());

        return ['message' => 'Chat Thread created'];

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
            'title' => 'required',
            'chat_channel_id' => 'required'
        ];
        if ($request->method() == 'PATCH') {
            $rules = [
                'title' => 'sometimes|required',
                'chat_channel_id' => 'sometimes|required',
            ];
        }

        $payload = app('request')->only('title', 'chat_channel_id');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        $chat_channel->update($request->only('title', 'chat_channel_id'));

        return $this->response->item($chat_channel->fresh(), new ChatThreadTransformer());

    }


    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        $chat_thread = $this->model->findOrFail($id);
        $chat_thread->delete();

        return $this->response->noContent();
    }


}
