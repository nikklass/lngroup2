<?php

namespace App\Http\Controllers\Api\Users;

use App\Entities\ConfirmCode;
use App\Entities\User;
use App\Http\Controllers\Controller;
use App\Transformers\Users\UserTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class UsersController.
 */
class UsersController extends Controller
{
    use Helpers;

    /**
     * @var User
     */
    protected $model;


    /**
     * UsersController constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
        //$this->middleware('permission:List users')->only('index');
        //$this->middleware('permission:Create users')->only('store');
        //$this->middleware('permission:List users')->only('show');
        //$this->middleware('permission:Update users')->only('update');
        $this->middleware('permission:Delete users')->only('destroy');
    }


    /**
     * Returns the Users resource with the roles relation.
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

        return $this->response->paginator($paginator, new UserTransformer());
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {

        //create new user object
        $users = new User();

        $querydata = $request->querydata;
        
        //filter results
        if ($querydata) { 
            $users = $users
                ->where('id', '!=', auth()->user()->id)
                ->where(function ($query) use ($querydata) {
                    $query->where('first_name', 'like', "%$querydata%")
                          ->orWhere('last_name', 'like', "%$querydata%"); 
                });
                
        }

        $users = $users->orderBy('first_name', 'asc');

        $paginator = $users->paginate($request->get('limit', config('app.pagination_limit')));
        if ($request->has('limit')) {
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new UserTransformer());

    }


    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $user = $this->model->with('roles.permissions')->byUuid($id)->firstOrFail();

        return $this->response->item($user, new UserTransformer());
    }


    /**
     * get logged in user
     */
    public function loggeduser()
    {
        $user = auth()->user();
        return $this->response->item($user, new UserTransformer());
    }
    

    /**
     * confirm an account
     * @param Request $request
     * @return mixed
     */
    public function accountconfirm(Request $request)
    {

        $rules = [
            'phone_country' => 'required_with:phone',
            'phone' => 'required|phone:mobile',
            'confirm_code' => 'required',
        ];

        $payload = app('request')->only('confirm_code', 'phone', 'phone_country');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            $error_messages = formatValidationErrors($validator->errors());
            throw new StoreResourceFailedException($error_messages);
        }

        //set variables
        $status_active = config('constants.status.active');
        $status_disabled = config('constants.status.disabled');

        $phone_country = $request->phone_country;
        $phone = $request->phone;
        $email = $request->email;
        $confirm_code = $request->confirm_code;
        
        //DB::enableQueryLog();

        //get user account if active, join to confirm_codes table where status_id = 1
        $local_phone = getLocalisedPhoneNumber($phone, $phone_country);
        $full_phone = getDatabasePhoneNumber($phone, $phone_country);
        
        $user = DB::table('users')
                        ->when($phone, function ($query) use ($local_phone, $phone_country) {
                            $query->where('users.phone', $local_phone)
                                  ->where('users.phone_country', $phone_country);
                        }, function ($query) use ($email) {
                            $query->where('users.email', $email);
                        })
                        /*->join('confirm_codes', function ($join) use ($confirm_code) {
                            $join->on('users.id', '=', 'confirm_codes.user_id')
                                 ->where('confirm_codes.status_id', '=', 1)
                                 ->where('confirm_codes.confirm_code', '=', $confirm_code);
                        })*/
                        ->first();

        /*dump("<pre>");
        print_r(DB::getQueryLog());
        dump("</pre>");*/

        if (!$user) {

            $error_message[] = 'User account does not exist.';
            $error_message = json_encode($error_message);
            throw new StoreResourceFailedException($error_message);

        } else {

            if ($user->active == 1) {

                $error_message[] = 'User account is already active.';
                $error_message = json_encode($error_message);
                throw new StoreResourceFailedException($error_message);

            } 

            //check if supplied code is active
            $code_data = ConfirmCode::where('confirm_code', '=', $confirm_code)
                            ->where('status_id', '=', $status_active)
                            ->when($phone, function ($query) use ($full_phone, $phone_country) {
                                $query->where('phone', $full_phone)
                                      ->where('phone_country', $phone_country);
                            }, function ($query) use ($email) {
                                $query->where('email', $email);
                            })
                            ->first();

            if (!$code_data) {

                $error_message[] = 'Invalid confirmation code.';
                $error_message = json_encode($error_message);
                throw new StoreResourceFailedException($error_message);

            } 
            //end check if supplied code is active

            //update the user record
            DB::table('users')
                ->when($phone, function ($query) use ($local_phone, $phone_country) {
                    $query->where('phone', $local_phone)
                          ->where('phone_country', $phone_country);
                }, function ($query) use ($email) {
                    $query->where('email', $email);
                })
                ->update(['status_id' => $status_active, 'active' => '1']);

            //update the confirm codes record, set to disabled
            $update_confirm_code = DB::table('confirm_codes')
                ->where('confirm_code', '=', $confirm_code)
                ->when($phone, function ($query) use ($full_phone, $phone_country) {
                    $query->where('phone', $full_phone)
                          ->where('phone_country', $phone_country);
                }, function ($query) use ($email) {
                    $query->where('email', $email);
                })
                ->update(['status_id' => $status_disabled]);

            //print_r(DB::getQueryLog());

            return ['message' => 'Welcome. Your account successfully confirmed.'];


        }

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
        return ['message' => 'User created. Please confirm your account.'];

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

        return $this->response->item($user->fresh(), new UserTransformer());

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
        $user->update($request->only('dob', 'dob_updated'));
        //$user->update($request->except('id'));

        return $this->response->item($user->fresh(), new UserTransformer());

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

        return $this->response->item($user->fresh(), new UserTransformer());

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
