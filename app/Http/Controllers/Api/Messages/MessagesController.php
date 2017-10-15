<?php

namespace App\Http\Controllers\Api\Messages;

use App\Entities\Message;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Transformers\Messages\MessageTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Http\Response\paginator;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\paginate;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Propaganistas\LaravelPhone\PhoneNumber;
use libphonenumber\PhoneNumberFormat;

class MessagesController extends Controller
{

    use Helpers;

    /**
     * @var Country
     */
    protected $model;

    /**
     * CountriesController constructor.
     *
     * @param Country $model
     */
    public function __construct(Message $model)
    {
        $this->model = $model;

        /*$this->middleware('permission:Create profiles')->only('store');
        $this->middleware('permission:Update profiles')->only('update');
        $this->middleware('permission:Delete profiles')->only('destroy');*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //are we in report mode?
        $report = $request->report;

        $message = $this->model->all();
        
        if (!$report) {
            $message = $message->paginate('limit', $request->get('limit', config('app.pagination_limit')));

            return $this->response->paginator($message, new MessageTransformer());
        }

        return $this->response->collection($message, new MessageTransformer);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'subject' => 'required',
            'message' => 'required'
        ];

        $payload = app('request')->only('subject', 'message');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException($validator->errors());
        }

        //create message
        $message = $this->model->create($request->all());

        return ['message' => 'Message successfully sent.'];

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        //get details for this message
        $message = $this->model->where('id', $id)
                 ->with('company')
                 ->first();

        return $this->response->item($message, new MessageTransformer());
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
