<?php

use App\Entities\Country;
use App\Role;
use App\RoleUser;
use App\User;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
* change plain number to formatted currency
*
* @param $number
* @param $currency
*/
function formatCurrency($number, $decimals = 2, $currency = 'Ksh')
{
    return $currency . " " . format_num($number, $decimals, '.', ',');
}

//format number 
function format_num($num, $decimals=2) {
	if (checkCharExists(',', $num)) {
		return $num;
	}
	return number_format($num, $decimals, '.', ',');
}

function checkCharExists($char, $value) {
	if (strpos($value, $char) !== false) {
	  return true;
	}
	return false;
}

//get url to be cached
function getFullCacheUrl($url, $params) {

        //Sorting query params by key (acts by reference)
        ksort($params);

        //Transforming the query array to query string
        $queryString = http_build_query($params);

        $fullUrl = "{$url}?{$queryString}";

        return $fullUrl;

}

//get cache duration - for caching pages
function getCacheDuration($config='') {
        
        if ($config == 'low') {
		    $minutes = config('app.cache_minutes_low');
		} else {
		    $minutes = config('app.cache_minutes');
		}

        return $minutes;

}

//format to local phone number
function getLocalisedPhoneNumber($phone_number, $country_code='KE') {    
	$phone_number = PhoneNumber::make($phone_number, $country_code)->formatNational();
	//remove spaces
	return str_replace(" ", "", $phone_number);

}

//format to international phone number with spaces in between
function getInternationalPhoneNumber($phone_number, $country_code='KE') {    
	return PhoneNumber::make($phone_number, $country_code)->formatInternational();
}

//format to international phone number with no spaces in between
function getInternationalPhoneNumberNoSpaces($phone_number, $country_code='KE') {    
	return PhoneNumber::make($phone_number, $country_code)->formatE164();
}

//format to db format, remove plus (+) sign
function getDatabasePhoneNumber($phone_number, $country_code='KE') {    
	$phone_number = PhoneNumber::make($phone_number, $country_code)->formatE164();
	return str_replace("+", "", $phone_number);
}

//format for dialling in country
function getForMobileDialingPhoneNumber($phone_number, $country_code='KE', $dialling_country_code='KE') {    
	return PhoneNumber::make($phone_number, $country_code)->formatForMobileDialingInCountry($dialling_country_code);
}

//format phone number
function formatPhoneNumber($phone_number, $country_code='KE') {    
	
	//get localised phone number
	$phone_number = PhoneNumber::make($phone_number, 'KE')->formatE164();
	//dd($phone_number);

	//get country data
	$country = Country::where('phonecode', $country_code)->first();
	$digits = $country->phonedigits;
	//dd($digits, $country);
	if (!$digits) { $digits = 10; }

	$phone_number = $country_code . substr(trim($phone_number),-$digits); 

	return $phone_number;
	
}


//current time
function getCurrentTime() {
    return Carbon\Carbon::now();
}


//array to object
function arrayToObject($array) {
    if (!is_array($array)) {
        return $array;
    }
    
    $object = new stdClass();
    if (is_array($array) && count($array) > 0) {
        foreach ($array as $name=>$value) {
            $name = strtolower(trim($name));
            if (!empty($name)) {
                $object->$name = arrayToObject($value);
            }
        }
        return $object;
    }
    else {
        return FALSE;
    }
}

//format api validation errors
function formatValidationErrors($errors) {
    //loop thru validation errors
    $error_messages = [];
    $messages = $errors->toArray();
    foreach ($messages as $message){
        $error_messages[] = $message[0];
    }
	$json = json_encode($error_messages);
    return $json;
}

//check for valid phone number
function isValidPhoneNumber($phone_number) {
        		
	$phone_number_status = false; 
	
	$phone_number = trim($phone_number);
	
	if (strlen($phone_number) == 12) 
	{
		$pattern = "/^2547(\d{8})$/";
		if (preg_match($pattern, $phone_number)) {
			$phone_number_status = true;
		}
	}
	
	if (strlen($phone_number) == 13) 
	{
		$pattern = "/^\+2547(\d{8})$/";
		if (preg_match($pattern, $phone_number)) {
			$phone_number_status = true;
		}
	}
	
	if (strlen($phone_number) == 10) 
	{
		$pattern = "/^07(\d{8})$/";
		if (preg_match($pattern, $phone_number)) {
			$phone_number_status = true;
		}
	}

	if (strlen($phone_number) == 9) 
	{
		$pattern = "/^7(\d{8})$/";
		if (preg_match($pattern, $phone_number)) {
			$phone_number_status = true;
		}
	}

    return  $phone_number_status;
	
}

//validate an email address
function validateEmail($email) {

	return preg_match("/^(((([^]<>()[\.,;:@\" ]|(\\\[\\x00-\\x7F]))\\.?)+)|(\"((\\\[\\x00-\\x7F])|[^\\x0D\\x0A\"\\\])+\"))@((([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))(\\.([[:alnum:]]([[:alnum:]]|-)*[[:alnum:]]))*|(#[[:digit:]]+)|(\\[([[:digit:]]{1,3}(\\.[[:digit:]]{1,3}){3})]))$/", $email);

}

//format date
function formatFriendlyDate($date) {
	return Carbon\Carbon::parse($date)->format('d-M-Y, H:i');
}

function downloadExcelFile($excel_name, $excel_title, $excel_desc, $data_array, $data_type) {

	Excel::create($excel_name, function($excel) use ($data_array) {

        // Set the spreadsheet title, creator, and description
        //$excel->setTitle($excel_title);
        $excel->setCreator(config('app.name'))->setCompany(config('app.name'));
        //$excel->setDescription($excel_desc);

        // Build the spreadsheet, passing in the payments array
        $excel->sheet('sheet1', function($sheet) use ($data_array) {
            $sheet->fromArray($data_array, null, 'A1', false, false);
            // Set auto size for sheet
			$sheet->setAutoSize(true);
        });

    })->download($data_type);

}

//shorten text title
function reducelength($str,$maxlength=100) {
	if (strlen($str) > $maxlength) {
		$newstr = substr($str,0,$maxlength-3) . "...";	
	} else {
		$newstr = $str;
	}
	return $newstr;
}

/// function to generate random number ///////////////
function generateCode($length = 5, $add_dashes = false, $available_sets = 'ud')
{
	$sets = array();
	if(strpos($available_sets, 'l') !== false)
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	if(strpos($available_sets, 'u') !== false)
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	if(strpos($available_sets, 'd') !== false)
		$sets[] = '23456789';
	if(strpos($available_sets, 's') !== false)
		$sets[] = '!@#$%&*?';
	$all = '';
	$password = '';
	foreach($sets as $set)
	{
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}
	$all = str_split($all);
	for($i = 0; $i < $length - count($sets); $i++)
		$password .= $all[array_rand($all)];
	$password = str_shuffle($password);
	if(!$add_dashes)
		return $password;
	$dash_len = floor(sqrt($length));
	$dash_str = '';
	while(strlen($password) > $dash_len)
	{
		$dash_str .= substr($password, 0, $dash_len) . '-';
		$password = substr($password, $dash_len);
	}
	$dash_str .= $password;
	return $dash_str;
} 
// end of generate random number function


function getLocalTimezone() {
	//get user timezone and return, 
	//if blank return default timezone ('Africa/Nairobi')
	$location = getUserLocation();
	$userTimezone=$location->timezone;
	if ($userTimezone) {
		$timezone = $userTimezone;
	} else {
		$timezone = config('app.local_timezone');
	}
	return $timezone;
}

function getUserAgent(){
	return @$_SERVER["HTTP_USER_AGENT"]?$_SERVER["HTTP_USER_AGENT"]: "" ;
}

function getIp(){
	$ip = request()->ip();
    return $ip;
}

function getUserLocation(){
    return geoip(getIp());
}

function getHost() {
	return @$_SERVER["REMOTE_HOST"]? $_SERVER["REMOTE_HOST"]: "" ; 
}

function getJsonOauthData() {
	
	$data = [
	        'json' => [
	            "grant_type"=> "password",
				"client_id"=> config('constants.oauth.client_id'),
				"client_secret"=> config('constants.oauth.client_secret'),
				"username"=> config('constants.oauth.username'),
				"password"=> config('constants.oauth.password'),
				"scope"=> ""
	        ]
	    ];

	return $data;

}

// fetching bulk sms data
function getBulkSMSData($sms_user_name) {

	if ($sms_user_name) {
		
		//get bulk sms data for this client
		$get_sms_data_url = config('constants.bulk_sms.get_sms_data_url');

		//url params
		$body['username'] = $sms_user_name;

		//get sms urls
		$get_oauth_token_url = config('constants.passport.token_url');

	    //get oauth access token
	    $tokenclient = getTokenGuzzleClient();

	    $oauth_json_data = getJsonOauthData();

	    $resp = $tokenclient->request('POST', $get_oauth_token_url, $oauth_json_data); 

	    if ($resp->getBody()) {
        
	        $result = json_decode($resp->getBody());
	        $access_token = $result->access_token;
	        $refresh_token = $result->refresh_token;

	        try {

	            //send request to send sms
	            $dataclient = getGuzzleClient($access_token);
	            $respons = $dataclient->request('GET', $get_sms_data_url, [
	                'query' => $body
	            ]);

	            if ($respons->getStatusCode() == 200) {

	                if ($respons->getBody()) {
	        
	                    $response = json_decode($respons->getBody());

	                } else {
				
						$response["error"] = true;
						$response["message"] = "An error occured while fetching bulk sms account";
						
				    }

				    return $response; 

	            }  

	        } catch (\Exception $e) {
	            dd($e);
	        }

	    } 

	} else {
				
		$response["error"] = true;
		$response["message"] = "No SMS account exists";
		
    }
	
	return $response; 
	
}

//send sms
function sendSms($params) {

	//get data array
	$body['usr'] = $params['usr'];
    $body['pass'] = $params['pass'];
    $body['src'] = $params['src'];
    $body['dest'] = $params['phone'];
    $body['msg'] = $params['message'];

	//get urls
	$get_oauth_token_url = config('constants.passport.token_url');
	$send_sms_url = config('constants.bulk_sms.send_sms_url');

    //get oauth access token
    $tokenclient = getTokenGuzzleClient();

    $oauth_json_data = getJsonOauthData();

	$resp = $tokenclient->request('POST', $get_oauth_token_url, $oauth_json_data);

    if ($resp->getBody()) {
        
        $result = json_decode($resp->getBody());
        $access_token = $result->access_token;
        $refresh_token = $result->refresh_token;

        try {

            //send request to send sms
            $dataclient = getGuzzleClient($access_token);
            $respons = $dataclient->request('POST', $send_sms_url, 
            	['query' => $body]
            );

            if ($respons->getStatusCode() == 200) {

                if ($respons->getBody()) {
        
                    $result = json_decode($respons->getBody());

                    // get results
					if  ($result->success) {
						
						//dd($access_token, $send_sms_url, $body);
						//show data
						$response["error"] = false;
						$response["message"] = $result->success->message;
				        
				    } else {
						
						$response["error"] = true;
						$response["message"] = $result->success->message;
						
				    }

                } else {
			
					$response["error"] = true;
					$response["message"] = "An error occured while sending sms";
					
			    }

			    return $response; 

            }  

        } catch (\Exception $e) {
            dd($e);
        }

    }

}


//check admin group ids
function getAdminGroupIds($user_account_team_ids, $permissions){
	
	$team_ids = [];
	
	if (auth()->user()->hasRole('superadministrator')){

		$team_ids = $user_account_team_ids;

	} else {
		
		foreach ($user_account_team_ids as $team_id) {
	        //if permissions is an array
	        if (is_array($permissions)) {
		        if (auth()->user()->can($permissions, $team_id, true)){
			        $team_ids[] = $team_id;
			    }
			} else {
				//check single permission
				if (auth()->user()->can($permissions, $team_id)){
			        $team_ids[] = $team_id;
			    }
			}
	    }

	} 

    return $team_ids;

}


function isAdminRoleError($rolesdata) {

	$error = false; 

	//check perms
    $team_ids = [];
    $role_ids = [];
    $role_names = [];
    $roles_data = explode(',', $rolesdata);
    
    foreach ($roles_data as $role) {

    	//split value to get roles - 2nd param
    	$role_data = explode('-', $role);

    	$team_id = $role_data[0];
    	$role_id = $role_data[1];

    	//get role name
    	$role_name_data = Role::findOrFail($role_id);
    	$role_name = $role_name_data->name;
    	
    	//check user permissions
    	if (checkAdminGroupIds($team_id, 'edit-user')) {
    		$error = false; 
    	} else {
    		$error = true; 
    		break;
    	}

    }

    return $error;

}


function isAdminGroupIdsError($user_account_team_ids, $permissions, $item_user_id=null, 
							  $is_owner_allowed=true, $any_user_allowed=false){
	
	$error = true;
	
	if (auth()->user()->hasRole('superadministrator')) {
		
		$error = false;

	} else {
		
		//dd($user_account_team_ids);
		//have we supplied team ids?
		if (count($user_account_team_ids)) {
			foreach ($user_account_team_ids as $team_id) {
		        //if permissions is an array
		        if (is_array($permissions)) {
			        if (auth()->user()->can($permissions, $team_id, true)){
				        $error = false;
				    }
				} else {
					//check single permission
					if (auth()->user()->can($permissions, $team_id)){
				        $error = false;
				    }
				}
		    }
		}

	    //are we still in error? and is any user allowed to perform this action? e.g make deposit
	    if ($error && $any_user_allowed) {
		    
		    //check if user belongs to a group
		    if (auth()->user()->id){
		        $error = false;
		    }

		}


		//are we still in error? and is item owner allowed to perform this action?
	    if ($error && $is_owner_allowed) {
		    
		    //check if logged user owns item
		    if (auth()->user()->id == $item_user_id){
		        $error = false;
		    }

		}

	} 

    return $error;

}


function checkDataPermissions($data)
{

    $valid = false;

    $team_ids = $data['team_ids'];
    $permissions = $data['permissions'];
    $item_user_id = $data['item_user_id'];
    $owner_allowed = $data['permissions'];
    $any_user_allowed = $data['any_user_allowed'];
    
    if (!isAdminGroupIdsError($team_ids, $permissions, $item_user_id, $owner_allowed, $any_user_allowed)){

         $valid = true;

    } 

	return $valid;

}

function getCheckdata($permissions, $team_ids, $item_user_id=null, $owner_allowed=null, $any_user_allowed=null)
{

    $checkdata = [];
    
    $checkdata['permissions'] = $permissions;
    $checkdata['team_ids'] = $team_ids;
    $checkdata['item_user_id'] = $item_user_id;
    $checkdata['owner_allowed'] = $owner_allowed;
    $checkdata['any_user_allowed'] = $any_user_allowed;

    return $checkdata;

}

//mpesa


/*
consumer_key_1

C2B
TransactionStatus
AccountBalance
Reversal
C2B PayBill
*/


/*
consumer_key_2

B2C
TransactionStatus
AccountBalance
Reversal
*/


function getMpesaTokenCredentials_1() {
	
    $mpesa_consumer_key_1 = config('constants.mpesa.consumer_key_1');
    $mpesa_consumer_secret_1 = config('constants.mpesa.consumer_secret_1');

    $credentials = base64_encode("$mpesa_consumer_key:$mpesa_consumer_secret"); 

    return $credentials;

}

function getMpesaTokenCredentials_2() {
	
    $mpesa_consumer_key = config('constants.mpesa.consumer_key_2');
    $mpesa_consumer_secret = config('constants.mpesa.consumer_secret_2');

    $credentials = base64_encode("$mpesa_consumer_key:$mpesa_consumer_secret"); 

    return $credentials;

}

function byteStr2byteArray($s) {
    return array_slice(unpack("C*", "\0".$s), 1);
}

function getMpesaSecurityCredentials() 
{ 
  	
  	$mpesa_initiator_password = config('constants.mpesa.consumer_secret');
  	//$mpesa_initiator_password = "Vu5TS3WX";

    //read cert
    $cert_path = Storage::disk('local')->get('cert/cert.cer');;
    $ssl = openssl_x509_parse($cert_path);
    //dd($ssl);

    openssl_public_encrypt($mpesa_initiator_password, $encrypted, $cert_path, OPENSSL_PKCS1_PADDING);

  	return(base64_encode($encrypted)); 

} 

function getGuzzleClient($token)
{
    return new \GuzzleHttp\Client([
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ],
    ]);
}

function getTokenGuzzleClient()
{
    return new \GuzzleHttp\Client([
        'headers' => [
            'Content-Type' => 'application/json'
        ],
    ]);
}

function getTokenGuzzleClient_2()
{
    //get mpesa credentials
    $credentials = getMpesaTokenCredentials_2(); 
    return new \GuzzleHttp\Client([
        'headers' => [
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/json'
        ],
    ]);
}

function createC2bTransactionRegisterUrl($validation_url, $confirmation_url) {
	
    $get_mpesa_token_url = config('constants.mpesa.get_mpesa_token_url');
    $c2b_register_url = config('constants.mpesa.c2b_register_url');
    $short_code = config('constants.mpesa.short_code');

    //get mpesa token
    $tokenclient = getTokenGuzzleClient();

    try {
    	$resp = $tokenclient->request('GET', $get_mpesa_token_url);  
    } catch (\Exception $e) {
        dd($e);
    }

    //dd(json_decode($resp->getBody())); 

    if ($resp->getBody()) {
        
        $result = json_decode($resp->getBody());
        $access_token = $result->access_token;

        try {

            //send request to mpesa
            //ReponseType - how mpesa responds in case of timeout = cancelled/completed
            $dataclient = getGuzzleClient($access_token);
            $response = $dataclient->request('POST', $c2b_register_url, [
                'json' => [
                    'ShortCode' => $short_code,
                    'ResponseType' => 'Cancelled',
                    'ConfirmationURL' => $confirmation_url,
                    'ValidationURL' => $validation_url
                ]
            ]);

            if ($response->getStatusCode() == 200) {

                if ($response->getBody()) {
        
                    $result = json_decode($response->getBody());

                    return $result;
                    

                }

            }  

        } catch (\Exception $e) {
            dump($e);
        }

    }

}

function createMpesac2bSimulateTransaction($amount, $phone_number, $billRefNumber, $commandId='CustomerPayBillOnline') {
	
	//commandId Options 
	/*
		CustomerPayBillOnline
 		CustomerBuyGoodsOnline
	*/
	$client = new \GuzzleHttp\Client();

    $get_mpesa_token_url = config('constants.mpesa.get_mpesa_token_url');
    $c2b_simulate_trans_url = config('constants.mpesa.c2b_simulate_trans_url');
    $short_code = config('constants.mpesa.short_code');

    //format phone number
    $phone_number = formatPhoneNumber($phone_number);

    //get mpesa token
    $tokenclient = getTokenGuzzleClient();

    try {
    	$resp = $tokenclient->request('GET', $get_mpesa_token_url);  
    } catch (\Exception $e) {
        dd($e);
    } 

    if ($resp->getBody()) {
        
        $result = json_decode($resp->getBody());
        $access_token = $result->access_token;

        try {

            //send request to mpesa
            $dataclient = getGuzzleClient($access_token);
            $response = $dataclient->request('POST', $c2b_simulate_trans_url, [
                'json' => [
                    'ShortCode' => $short_code,
                    'CommandID' => $commandId,
                    'Amount' => $amount,
                    'Msisdn' => $phone_number,
                    'BillRefNumber' => $billRefNumber
                ]
            ]);

            if ($response->getStatusCode() == 200) {

                if ($response->getBody()) {
        
                    $result = json_decode($response->getBody());

                    return $result;
                    

                }

            }  

        } catch (\Exception $e) {
            dump($e);
        }

    }

}

/*B2B Payment Request*/
function createMpesab2bPaymentRequest($username, $receiver_short_code, $amount, $remarks, $account_reference, $queue_timeout_url, $result_url, $command_id='BusinessPayBill') {
	
	//commandId Options 
	/*

		BusinessPayBill
		BusinessBuyGoods
		DisburseFundsToBusiness
		BusinessToBusinessTransfer
		BusinessTransferFromMMFToUtility
		BusinessTransferFromUtilityToMMF
		MerchantToMerchantTransfer
		MerchantTransferFromMerchantToWorking
		MerchantServicesMMFAccountTransfer
		AgencyFloatAdvance

	*/

	/*	
		SenderIdentifierType/ ReceiverIdentifierType

		1 – MSISDN
		2 – Till Number
		4 – Organization short code

	*/

    $get_mpesa_token_url = config('constants.mpesa.get_mpesa_token_url');
    $b2b_payment_request_url = config('constants.mpesa.b2b_payment_request_url');
    $short_code = config('constants.mpesa.short_code');

    //get mpesa token
    $tokenclient = getTokenGuzzleClient();

    try {
    	$resp = $tokenclient->request('GET', $get_mpesa_token_url);  
    } catch (\Exception $e) {
        dd($e);
    }  

    if ($resp->getBody()) {
        
        $result = json_decode($resp->getBody());
        $access_token = $result->access_token;
        $mpesa_security_credentials = getMpesaSecurityCredentials();

        try {

            //send request to mpesa
            $body = [
                'json' => [
                    'Initiator' => $username,
                    'CommandID' => $command_id,
                    'Amount' => $amount,
                    'SecurityCredential' => $mpesa_security_credentials,
                    'SenderIdentifierType' => 4,
                    'RecieverIdentifierType' => 4,
                    'PartyA' => $short_code,
                    'PartyB' => $receiver_short_code,
                    'AccountReference' => $account_reference,
                    'Remarks' => $remarks,
                    'QueueTimeOutURL' => $queue_timeout_url,
                    'ResultURL' => $result_url
                ]
            ];

            $dataclient = getGuzzleClient($access_token);
            $response = $dataclient->request('POST', $b2b_payment_request_url, $body);

            if ($response->getStatusCode() == 200) {

                if ($response->getBody()) {
        
                    $result = json_decode($response->getBody());

                    return $result;
                    

                }

            }  

        } catch (\Exception $e) {
            dump($e);
        }

    }

}


/*B2C Payment Request*/
function createMpesab2cPaymentRequest($username, $sender_short_code, $receiver_short_code, $amount, $remarks, $occassion, $queue_timeout_url, $result_url, $command_id='BusinessPayment') {
	
	//commandId Options 
	/*
		SalaryPayment
		BusinessPayment
		PromotionPayment
	*/

    $get_mpesa_token_url = config('constants.mpesa.get_mpesa_token_url');
    $b2c_payment_request_url = config('constants.mpesa.b2c_payment_request_url');
    $short_code = config('constants.mpesa.short_code');

    if ($sender_short_code) {
    	$short_code = $sender_short_code;
    }

    //get mpesa token
    $tokenclient = getTokenGuzzleClient_2();

    try {
    	$resp = $tokenclient->request('GET', $get_mpesa_token_url);  
    } catch (\Exception $e) {
        dd($e);
    }

    if ($resp->getBody()) {
        
        $result = json_decode($resp->getBody());
        $access_token = $result->access_token;
        $mpesa_security_credentials = getMpesaSecurityCredentials();
        //dd($access_token, $mpesa_security_credentials);

        try {

            //send request to mpesa
            $body = [
                'json' => [
                    'InitiatorName' => $username,
                    'CommandID' => $command_id,
                    'Amount' => $amount,
                    'SecurityCredential' => $mpesa_security_credentials,
                    'PartyA' => $short_code,
                    'PartyB' => $receiver_short_code,
                    'Remarks' => $remarks,
                    'QueueTimeOutURL' => $queue_timeout_url,
                    'ResultURL' => $result_url,
                    'Occassion' => $occassion
                ]
            ];
            dd($body);
            $dataclient = getGuzzleClient($access_token);
            $response = $dataclient->request('POST', $b2c_payment_request_url, $body);

            if ($response->getStatusCode() == 200) {

                if ($response->getBody()) {
        
                    $result = json_decode($response->getBody());
                    dd($result);

                    return $result;

                }

            }  

        } catch (\Exception $e) {
            dump($e);
        }

    }

}


/*Get Mpesa Account Balance*/
function getMpesaAccountBalance($username, $receiver_short_code, $remarks, $queue_timeout_url, $result_url,  $receiver_identifier='4') {

    $get_mpesa_token_url = config('constants.mpesa.get_mpesa_token_url');
    $account_balance_url = config('constants.mpesa.account_balance_url');
    $short_code = config('constants.mpesa.short_code');

    if ($receiver_short_code) {
    	$short_code = $receiver_short_code;
    }

    //get mpesa token
    $tokenclient = getTokenGuzzleClient();

    try {
    	$resp = $tokenclient->request('GET', $get_mpesa_token_url);  
    } catch (\Exception $e) {
        dd($e);
    } 

    if ($resp->getBody()) {
        
        $result = json_decode($resp->getBody());
        $access_token = $result->access_token;
        $mpesa_security_credentials = getMpesaSecurityCredentials();

        try {
        	//$remarks = "sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks sample remarks ";
            //send request to mpesa
            $body = [
                'json' => [
                    'Initiator' => $username,
                    'CommandID' => 'AccountBalance',
                    'SecurityCredential' => $mpesa_security_credentials,
                    'IdentifierType' => 6,
                    'PartyA' => $short_code,
                    'Remarks' => $remarks,
                    'QueueTimeOutURL' => $queue_timeout_url,
                    'ResultURL' => $result_url
                ]
            ];
            //dd($body);
            
            $dataclient = getGuzzleClient($access_token);
            $response = $dataclient->request('POST', $account_balance_url, $body);

            if ($response->getStatusCode() == 200) {

                if ($response->getBody()) {
        
                    $result = json_decode($response->getBody());
                    dd($result);

                    return $result;

                }

            }  

        } catch (\Exception $e) {
            dd($e);
        }

    }

}

/*Create Lipa na mpesa Online Payment*/
function createLipaMpesaOnlinePayment($amount, $phone_number, $callback_url, $account_reference, $transaction_desc) {

    $get_mpesa_token_url = config('constants.mpesa.get_mpesa_token_url');
    $lipa_mpesa_online_payment_url = config('constants.mpesa.lipa_mpesa_online_payment_url');
    $short_code = config('constants.mpesa.short_code');
    $business_short_code = config('constants.mpesa.short_code');
    $lipa_mpesa_password = config('constants.mpesa.lipa_mpesa_password');

    //get mpesa token
    $tokenclient = getTokenGuzzleClient();

    try {
    	$resp = $tokenclient->request('GET', $get_mpesa_token_url);  
    } catch (\Exception $e) {
        dd($e);
    } 

    if ($resp->getBody()) {
        
        $result = json_decode($resp->getBody());
        $access_token = $result->access_token;
        $time = date('YmdHis');
        $password = base64_encode("$business_short_code:$lipa_mpesa_password:$time");

        try {

            //send request to mpesa
            $dataclient = getGuzzleClient($access_token);
            $response = $dataclient->request('POST', $lipa_mpesa_online_payment_url, [
                'json' => [
                    'BusinessShortCode' => $business_short_code,
                    'Password' => $password,
                    'Timestamp' => $time,
                    'TransactionType' => 'CustomerPayBillOnline',
                    'Amount' => $amount,
                    'PartyA' => $short_code,
                    'PartyB' => $short_code,
                    'PhoneNumber' => $phone_number,
                    'CallBackURL' => $callback_url,
                    'AccountReference' => $account_reference,
                    'TransactionDesc' => $transaction_desc
                ]
            ]);

            if ($response->getStatusCode() == 200) {

                if ($response->getBody()) {
        
                    $result = json_decode($response->getBody());

                    return $result;

                }

            }  

        } catch (\Exception $e) {
            dump($e);
        }

    }

}

function users()
{
    return User::join('role_user', 'users.id', 'user_id')
    		->orderBy('first_name');
}

function useraccounts()
{
    $user_role_id = Role::where('name', 'user')->pluck('id');
    return RoleUser::where('role_id', '=', $user_role_id)
            ->join('users', 'user_id', 'users.id')
            ->where('team_id', '!=', '1')
            ->orderBy('first_name');
}

function adminaccounts()
{
    $user_role_id = Role::where('name', 'user')->pluck('id');
    return RoleUser::where('role_id', '!=', $user_role_id)
    		->join('users', 'user_id', 'users.id')
            ->orderBy('first_name');
}

function allaccounts()
{
    return RoleUser::join('users', 'user_id', 'users.id')
            ->orderBy('first_name');
}
