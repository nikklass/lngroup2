<?php

//Route::get('/api', 'ApiDocsController@index');

//Route::get('/', 'Web\HomeController@index')->name('home');

/*Route::group(['middleware' => 'auth'], function() {

	Route::post('logout', 'Auth\LoginController@logout')->name('logout'); */
	
	//export to excel data...
	/*Route::get('excel/export-smsoutbox/{type}', 'ExcelController@exportOutboxSmsToExcel')->name('excel.export-smsoutbox');
	Route::get('excel/export-groups/{type}', 'ExcelController@exportGroupsToExcel')->name('excel.export-groups');
	Route::get('excel/mpesa-incoming/{type}', 'ExcelController@exportMpesaIncomingToExcel')->name('excel.mpesa-incoming');*/

	

	//handle bulk import user...
	/*Route::get('users/create-bulk', 'UserImportController@create')->name('bulk-users.create');
	Route::post('users/create-bulk', 'UserImportController@store')->name('bulk-users.store');
	Route::get('users/create-bulk/get-data/{uuid}', 'UserImportController@getImportData')->name('bulk-users.getimportdata');
	Route::get('users/create-bulk/get-incomplete/{uuid}', 'UserImportController@getIncompleteData')->name('bulk-users.getincompletedata');
	
	//send email routes...
	Route::get('/email/newUser', 'EmailController@newUserEmail')->name('email.newuser');

	//user routes...
	Route::resource('/users', 'UserController');

	//user profile routes...
	Route::get('/profile/{id}', 'ProfileController@indexId')->name('user.profile.id');
	Route::get('/profile', 'ProfileController@index')->name('user.profile'); 

	//role routes...
	Route::resource('/roles', 'RoleController', ['except' => 'destroy']);

	//group routes...
	Route::resource('/groups', 'GroupController');

	//mpesa-incoming routes...
	Route::resource('/mpesa-incoming', 'MpesaIncomingController');	

	//smsoutbox routes...
	Route::resource('/smsoutbox', 'SmsOutboxController', ['except' => ['edit', 'destroy']]);

	//schedule smsoutbox routes...
	Route::resource('/scheduled-smsoutbox', 'ScheduleSmsOutboxController');*/

//});

//superadmin routes
/*Route::group(['middleware' => 'role:superadministrator'], function() {
	//permission routes...
	Route::resource('/permissions', 'PermissionController', ['except' => 'destroy']);

	//mpesa paybills routes
	Route::resource('/mpesa-paybills', 'MpesaPaybillController');
});

//superadmin and admin routes
Route::group(['middleware' => 'role:superadministrator|administrator'], function() {
	//companies routes...
	Route::resource('/companies', 'CompanyController');
});

Route::group(['middleware' => 'guest'], function() {

	// Authentication Routes...
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('login', 'Auth\LoginController@login')->name('login.store');

	// Password Reset Routes...
	Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset.store');
	Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

	Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
	Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

});*/


