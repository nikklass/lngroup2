<?php

namespace App\Http\Controllers\Api\ChatMessages;

use App\Entities\ChatMessage;
use App\Http\Controllers\Controller;
use App\Transformers\ChatMessages\ChatMessageTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ChatMessagesController.
 */
class ChatMessagesController extends Controller
{
    use Helpers;

    /**
     * @var ChatMessage
     */
    protected $model;


    /**
     * ChatsController constructor.
     *
     * @param ChatMessage $model
     */
    public function __construct(ChatMessage $model)
    {
        $this->model = $model;
        /*$this->middleware('permission:List chatchannels')->only('index');
        $this->middleware('permission:Create chatchannels')->only('store');
        $this->middleware('permission:List chatchannels')->only('show');
        $this->middleware('permission:Update chatchannels')->only('update');
        $this->middleware('permission:Delete chatchannels')->only('destroy');*/
    }


    /**
     * Returns the ChatMessages resource with the roles relation.
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

        $chat_messages = $this->model;

        if ($request->chat_thread_id) {
            $chat_messages = $chat_messages->where('chat_thread_id', $request->chat_thread_id);
        }

        $paginator = $chat_messages->where('status_id', $status_id)
                     ->orderBy('id', 'desc')
                     ->paginate($request->get('limit', config('app.pagination_limit')));

        $data = $this->response->paginator($paginator, new ChatMessageTransformer());

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

        $chat_message = $this->model->findOrFail($id);

        $data = $this->response->item($chat_message, new ChatMessageTransformer());

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
                    'user_id' => $user_id
                ));

        $rules = [
            'chat_text' => 'required'
        ];

        $payload = app('request')->only('chat_text');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        //create channel
        $chat_message = $this->model->create($request->all());

        return ['message' => 'Chat Message created'];

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

        return $this->response->item($chat_channel->fresh(), new ChatMessageTransformer());

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
