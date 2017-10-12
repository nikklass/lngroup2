<?php

namespace App\Http\Controllers\Api\Cities;

use App\Entities\City;
use App\Http\Controllers\Controller;
use App\Transformers\Cities\CityTransformer;
use Carbon\Carbon;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Http\Response\paginator;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\paginate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class CitiesController.
 *
 */
class CitiesController extends Controller
{
    use Helpers;

    /**
     * @var City
     */
    protected $model;

    /**
     * CitiesController constructor.
     *
     * @param City $model
     */
    public function __construct(City $model)
    {
        $this->model = $model;

        $this->middleware('permission:Create acls')->only('store');
        $this->middleware('permission:Update acls')->only('update');
        $this->middleware('permission:Delete acls')->only('destroy');
    }

    public function index(Request $request)
    {

        //are we in report mode?
        $report = $request->report;
        $state_id = $request->state_id;

        $cities = new City();

        if ($state_id) {
            //get state cities
            $cities = $cities->where('state_id', $state_id);
        }

        $cities = $cities->orderBy('name', 'asc');
        
        if (!$report) {
            $cities = $cities->paginate('limit', $request->get('limit', config('app.pagination_limit')));

            return $this->response->paginator($cities, new CityTransformer());
        }

        $cities = $cities->get();

        return $this->response->collection($cities, new CityTransformer);

    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $state = $this->model->findOrFail($id);

        return $this->response->item($state, new CityTransformer());
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
            'email' => 'email|unique:users,email',
            'phone_number' => 'required|max:13',
            'password' => 'required|min:6|confirmed'
        ];

        $payload = app('request')->only('first_name', 'last_name', 'email', 'phone_number', 'password', 'password_confirmation');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Could not create new user.', $validator->errors());
        }

        //set user attributes
        $attributes = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => formatPhoneNumber($request->phone_number),
            'email' => $request->email,
            'gender' => $request->gender,
            'country_id' => $request->country_id,
            'password' => $request->password,
            'src_host' => getUserAgent(),
            'src_ip' => getIp(),
            'created_at' => getCurrentTime()
        ];

        //create user
        $user = $this->model->create($attributes);

        //$user = $this->model->create($request->all());

        if ($request->has('roles')) {
            $user->syncRoles($request['roles']);
        }

        //return $this->response->created(url('api/users/'.$user->uuid));
        return $this->response->created();

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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ];
        if ($request->method() == 'PATCH') {
            $rules = [
                'name' => 'sometimes|required',
                'email' => 'sometimes|required|email|unique:users,email,'.$user->id,
            ];
        }
        $this->validate($request, $rules);
        // Except password as we don't want to let the users change a password from this endpoint
        $user->update($request->except('_token', 'password'));
        if ($request->has('roles')) {
            $user->syncRoles($request['roles']);
        }

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
