<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){

    $api->group(['middleware' => ['throttle:60,1', 'bindings'], 'namespace' => 'App\Http\Controllers'], function($api) {

        $api->get('ping', 'Api\PingController@index');

        //login and refresh token
        $api->post('/login', 'Api\Users\LoginController@login');

        //sms routes
        $api->group(['prefix' => 'sms'],function ($api) {
            $api->get('/registration', 'Api\Sms\SmsOutboxController@store');
            $api->post('/registration', 'Api\Sms\SmsOutboxController@store');
        });

        //countries
        $api->group(['prefix' => 'countries'], function ($api) {
            $api->get('/', 'Api\Countries\CountriesController@index');
            $api->get('/{id}', 'Api\Countries\CountriesController@show');
        });

        //states
        $api->group(['prefix' => 'states'], function ($api) {
            $api->get('/', 'Api\States\StatesController@index');
            $api->get('/{id}', 'Api\States\StatesController@show');
        });

        //cities
        $api->group(['prefix' => 'cities'], function ($api) {
            $api->get('/', 'Api\Cities\CitiesController@index');
            $api->get('/{id}', 'Api\Cities\CitiesController@show');
        });

        //constituencies
        $api->group(['prefix' => 'constituencies'], function ($api) {
            $api->get('/', 'Api\Constituencies\ConstituenciesController@index');
            $api->get('/{id}', 'Api\Constituencies\ConstituenciesController@show');
        });

        //wards
        $api->group(['prefix' => 'wards'], function ($api) {
            $api->get('/', 'Api\Wards\WardsController@index');
            $api->get('/{id}', 'Api\Wards\WardsController@show');
        });

        //oauth clients
        $api->group(['prefix' => 'oauthclients'], function ($api) {
            $api->get('/', 'Api\Users\OauthClientsController@index');
            $api->get('/{id}', 'Api\Users\OauthClientsController@show');
        });

        //create user
        $api->group(['prefix' => 'users'], function ($api) {
            $api->post('/', 'Api\Users\UsersController@store');
            $api->post('/confirm', 'Api\Users\UsersController@accountconfirm');
        });
        

        $api->group(['middleware' => ['auth:api'], ], function ($api) {

            //users
            $api->group(['prefix' => 'users'], function ($api) {
                $api->get('/', 'Api\Users\UsersController@index');
                $api->get('/search', 'Api\Users\UsersController@search');
                $api->get('/{uuid}', 'Api\Users\UsersController@show');
                $api->put('/{uuid}', 'Api\Users\UsersController@update');
                $api->patch('/{uuid}', 'Api\Users\UsersController@update');
                $api->delete('/{uuid}', 'Api\Users\UsersController@destroy');

                /*change user password*/
                $api->post('/changePassword/{uuid}', 'Api\Users\UsersController@changePassword');

                /*update dob*/
                $api->put('/dob/{uuid}', 'Api\Users\UsersController@updateDob');
                $api->patch('/dob/{uuid}', 'Api\Users\UsersController@updateDob');

                /*update dob*/
                $api->put('/location/{uuid}', 'Api\Users\UsersController@updateLocation');
                $api->patch('/location/{uuid}', 'Api\Users\UsersController@updateLocation');

            });

            //user
            $api->group(['prefix' => 'user'], function ($api) {
                $api->get('/', 'Api\Users\UsersController@loggeduser');
            });

            //tokens
            $api->post('/login/refresh', 'Api\Users\LoginController@refresh');
            $api->post('/login/validateToken', 'Api\Users\LoginController@validateToken');
            

            //sms routes
            $api->group(['prefix' => 'sms'],function ($api) {
                $api->get('/getaccount', 'Api\Sms\SmsOutboxController@getBulkSmsAccount');
                $api->get('/getinbox', 'Api\Sms\SmsOutboxController@smsInbox');
                $api->get('/sendsms', 'Api\Sms\SmsOutboxController@sendBulkSms');
                $api->post('/sendsms', 'Api\Sms\SmsOutboxController@sendBulkSms');
            });

            //messages routes
            $api->group(['prefix' => 'messages'],function ($api) {
                $api->get('/', 'Api\Messages\MessagesController@index');
                $api->post('/', 'Api\Messages\MessagesController@store');
                $api->get('/{id}', 'Api\Messages\MessagesController@show');
                $api->put('/{id}', 'Api\Messages\MessagesController@update');
                $api->patch('/{id}', 'Api\Messages\MessagesController@update');
                $api->delete('/{id}', 'Api\Messages\MessagesController@destroy');
            });

            //countries
            $api->group(['prefix' => 'countries'], function ($api) {
                $api->post('/', 'Api\Countries\CountriesController@store');
                $api->put('/{id}', 'Api\Countries\CountriesController@update');
                $api->delete('/{id}', 'Api\Countries\CountriesController@destroy');
            });

            //states
            $api->group(['prefix' => 'states'], function ($api) {
                $api->post('/', 'Api\States\StatesController@store');
                $api->put('/{id}', 'Api\States\StatesController@update');
                $api->delete('/{id}', 'Api\States\StatesController@destroy');
            });

            //chatchannels routes
            $api->group(['prefix' => 'chatchannels'],function ($api) {
                $api->get('/', 'Api\ChatChannels\ChatChannelsController@index');
                $api->post('/', 'Api\ChatChannels\ChatChannelsController@store');
                $api->get('/{id}', 'Api\ChatChannels\ChatChannelsController@show');
                $api->put('/{id}', 'Api\ChatChannels\ChatChannelsController@update');
                $api->patch('/{id}', 'Api\ChatChannels\ChatChannelsController@update');
                $api->delete('/{id}', 'Api\ChatChannels\ChatChannelsController@destroy');
            });

            //chatmessagereadstates routes
            $api->group(['prefix' => 'chatmessagereadstates'],function ($api) {
                $api->get('/', 'Api\ChatMessageReadStates\ChatMessageReadStatesController@index');
                $api->post('/', 'Api\ChatMessageReadStates\ChatMessageReadStatesController@store');
                $api->get('/{id}', 'Api\ChatMessageReadStates\ChatMessageReadStatesController@show');
                $api->put('/{id}', 'Api\ChatMessageReadStates\ChatMessageReadStatesController@update');
                $api->patch('/{id}', 'Api\ChatMessageReadStates\ChatMessageReadStatesController@update');
                $api->delete('/{id}', 'Api\ChatMessageReadStates\ChatMessageReadStatesController@destroy');
            });

            //chatmessages routes
            $api->group(['prefix' => 'chatmessages'],function ($api) {
                $api->get('/', 'Api\ChatMessages\ChatMessagesController@index');
                $api->post('/', 'Api\ChatMessages\ChatMessagesController@store');
                $api->get('/{id}', 'Api\ChatMessages\ChatMessagesController@show');
                $api->put('/{id}', 'Api\ChatMessages\ChatMessagesController@update');
                $api->patch('/{id}', 'Api\ChatMessages\ChatMessagesController@update');
                $api->delete('/{id}', 'Api\ChatMessages\ChatMessagesController@destroy');
            });

            //chatthreads routes
            $api->group(['prefix' => 'chatthreads'],function ($api) {
                $api->get('/', 'Api\ChatThreads\ChatThreadsController@index');
                $api->post('/', 'Api\ChatThreads\ChatThreadsController@store');
                $api->get('/{id}', 'Api\ChatThreads\ChatThreadsController@show');
                $api->put('/{id}', 'Api\ChatThreads\ChatThreadsController@update');
                $api->patch('/{id}', 'Api\ChatThreads\ChatThreadsController@update');
                $api->delete('/{id}', 'Api\ChatThreads\ChatThreadsController@destroy');
            });

            //cities
            $api->group(['prefix' => 'cities'], function ($api) {
                $api->post('/', 'Api\Cities\CitiesController@store');
                $api->put('/{id}', 'Api\Cities\CitiesController@update');
                $api->delete('/{id}', 'Api\Cities\CitiesController@destroy');
            });

            $api->group(['prefix' => 'roles'], function ($api) {
                $api->get('/', 'Api\Users\RolesController@index');
                $api->post('/', 'Api\Users\RolesController@store');
                $api->get('/{uuid}', 'Api\Users\RolesController@show');
                $api->put('/{uuid}', 'Api\Users\RolesController@update');
                $api->patch('/{uuid}', 'Api\Users\RolesController@update');
                $api->delete('/{uuid}', 'Api\Users\RolesController@destroy');
            });

            $api->get('permissions', 'Api\Users\PermissionsController@index');

            $api->group(['prefix' => 'me'], function($api) {
                $api->get('/', 'Api\Users\ProfileController@index');
                $api->put('/', 'Api\Users\ProfileController@update');
                $api->patch('/', 'Api\Users\ProfileController@update');
                $api->put('/password', 'Api\Users\ProfileController@updatePassword');
            });

            $api->group(['prefix' => 'assets'], function($api) {
                $api->post('/', 'Api\Assets\UploadFileController@store');
            });

        });

    });

});



