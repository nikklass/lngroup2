<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Entities\Role;
use App\Entities\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class HomeController extends Controller
{

    /**
     * Show home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //return home 
        return view('site.index');

    }

}
