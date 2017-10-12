<?php

namespace App\Http\Controllers\Api\Chats;

use App\Entities\Chat;
use App\Http\Controllers\Controller;
use App\Transformers\Chats\ChatTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ChatsController.
 */
class ChatsController extends Controller
{
    use Helpers;

    /**
     * @var Chat
     */
    protected $model;


    /**
     * ChatsController constructor.
     *
     * @param Chat $model
     */
    public function __construct(Chat $model)
    {
        $this->model = $model;
        //$this->middleware('permission:List users')->only('index');
        //$this->middleware('permission:Create users')->only('store');
        //$this->middleware('permission:List users')->only('show');
        //$this->middleware('permission:Update users')->only('update');
        $this->middleware('permission:Delete users')->only('destroy');
    }


    /**
     * Returns the Chats resource with the roles relation.
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $paginator = $this->model->with('roles.permissions')->paginate($request->get('limit', config('app.pagination_limit')));
        if ($request->has('limit')) {
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new ChatTransformer());
    }


    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $user = $this->model->with('roles.permissions')->byUuid($id)->firstOrFail();

        return $this->response->item($user, new ChatTransformer());
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function addChannel($channelName)
    {

        //create
        $user = $this->model->create($request->all());

        //attach roles if any
        if ($request->has('roles')) {
            $user->syncRoles($request['roles']);
        }

        //return $this->response->created();
        return ['message' => 'Chat created. Please confirm your account.'];

    }
    

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone_country' => 'required_with:phone',
            'phone' => 'required|phone:mobile|unique:users',
        ];

        $payload = app('request')->only('first_name', 'last_name', 'email', 'phone', 'phone_country', 'password', 'password_confirmation');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        //create user
        $user = $this->model->create($request->all());

        //attach roles if any
        if ($request->has('roles')) {
            $user->syncRoles($request['roles']);
        }

        //return $this->response->created();
        return ['message' => 'Chat created. Please confirm your account.'];

    }

    /*change password -- cant*/
    public function changePassword(Request $request, $uuid)
    {
        
        $rules = [
            'oldpassword' => 'required',
            'newpassword' => 'required',
        ];
        if ($request->method() == 'PATCH') {
            $rules = [
                'oldpassword' => 'sometimes|required',
                'newpassword' => 'sometimes|required',
            ];
        }

        $payload = app('request')->only('oldpassword', 'newpassword');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        //DB::enableQueryLog();
            
        //find user and save new password
        $user = $this->model->byUuid($uuid)
                     //->where('password', bcrypt($request->oldpassword))
                     ->firstOrFail();
                     //->first();

        //print_r(DB::getQueryLog());

        //dd($user);
        $user->password = bcrypt($request->newpassword);
        $user->save();

        return ['message' => 'Password changed successfully.'];

    }

    /**
     * @param Request $request
     * @param $uuid
     * @return mixed
     */
    public function update(Request $request, $uuid)
    {
        
        $user = $this->model->byUuid($uuid)->firstOrFail();
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
        ];
        if ($request->method() == 'PATCH') {
            $rules = [
                'first_name' => 'sometimes|required',
                'last_name' => 'sometimes|required',
            ];
        }

        $payload = app('request')->only('first_name', 'last_name');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        // Except these as we don't want to let the users change these fields from this endpoint
        $user->update($request->except('_token', 'password', 'email', 'phone', 'phone_country'));
        /*if ($request->has('roles')) {
            $user->syncRoles($request['roles']);
        }*/

        return $this->response->item($user->fresh(), new ChatTransformer());

    }

    /**
     * @param Request $request
     * @param $uuid
     * @return mixed
     */
    public function updateDob(Request $request, $uuid)
    {
        
        $user = $this->model->byUuid($uuid)
                ->where('dob_updated', '0')
                ->firstOrFail();
        $rules = [
            'dob' => 'required',
            'dob_updated' => 'required',
        ];
        if ($request->method() == 'PATCH') {
            $rules = [
                'dob' => 'sometimes|required',
                'dob_updated' => 'sometimes|required',
            ];
        }

        $payload = app('request')->only('dob', 'dob_updated');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        // Except these as we don't want to let the users change these fields from this endpoint
        //$user->update($request->only('dob', 'dob_updated'));
        $user->update($request->except('id'));

        return $this->response->item($user->fresh(), new ChatTransformer());

    }

    /**
     * @param Request $request
     * @param $uuid
     * @return mixed
     */
    public function updateLocation(Request $request, $uuid)
    {
        
        $user = $this->model->byUuid($uuid)->firstOrFail();
        $rules = [
            'phone_country' => 'required',
            'state_id' => 'required',
        ];
        if ($request->method() == 'PATCH') {
            $rules = [
                'phone_country' => 'sometimes|required',
                'state_id' => 'sometimes|required',
            ];
        }

        $payload = app('request')->only('phone_country', 'state_id');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        // Except these as we don't want to let the users change these fields from this endpoint
        $user->update($request->except('id'));

        return $this->response->item($user->fresh(), new ChatTransformer());

    }

    /**
     * @param Request $request
     * @param $uuid
     * @return mixed
     */
    public function destroy(Request $request, $uuid)
    {
        $user = $this->model->byUuid($uuid)->firstOrFail();
        $user->delete();

        return $this->response->noContent();
    }
}
