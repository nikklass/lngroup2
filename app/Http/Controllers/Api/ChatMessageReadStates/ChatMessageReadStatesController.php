<?php

namespace App\Http\Controllers\Api\ChatMessageReadStates;

use App\Entities\ChatMessageReadState;
use App\Http\Controllers\Controller;
use App\Transformers\ChatMessageReadStates\ChatMessageReadStateTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ChatMessageReadStatesController.
 */
class ChatMessageReadStatesController extends Controller
{
    use Helpers;

    /**
     * @var ChatMessageReadState
     */
    protected $model;


    /**
     * ChatsController constructor.
     *
     * @param ChatMessageReadState $model
     */
    public function __construct(ChatMessageReadState $model)
    {
        $this->model = $model;
        /*$this->middleware('permission:List chatchannels')->only('index');
        $this->middleware('permission:Create chatchannels')->only('store');
        $this->middleware('permission:List chatchannels')->only('show');
        $this->middleware('permission:Update chatchannels')->only('update');
        $this->middleware('permission:Delete chatchannels')->only('destroy');*/
    }


    /**
     * Returns the ChatMessageReadStates resource with the roles relation.
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
        
        $chat_message_read_states = $this->model;

        if ($request->chat_thread_id) {
            $chat_message_read_states = $chat_message_read_states->where('chat_message_id', $request->chat_thread_id);
        }

        $paginator = $chat_message_read_states->orderBy('id', 'desc')
                     ->paginate($request->get('limit', config('app.pagination_limit')));

        $data = $this->response->paginator($paginator, new ChatMessageReadStateTransformer());

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

        $chat_message_read_state = $this->model->findOrFail($id);

        $data = $this->response->item($chat_message_read_state, new ChatMessageReadStateTransformer());

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
            'chat_thread_id' => 'required'
        ];

        $payload = app('request')->only('chat_thread_id');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        //create item
        $chat_message_read_state = $this->model->create($request->all());

        return ['message' => 'Chat Message Read States created'];

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

        return $this->response->item($chat_channel->fresh(), new ChatMessageReadStateTransformer());

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
