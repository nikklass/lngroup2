<?php

namespace App\Validators;

use App\Entities\User;
use Illuminate\Validation\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

class CustomRule extends Validator
{

    public function validateUniquePhone($attribute, $value, $parameters, $validator)
    {

        //get validator data
        $data = $validator->getData();
        $phone_country = $data['phone_country'];
        $phone = $value;

        //proceed if we have the right phone
        if (PhoneNumber::make($phone, $phone_country)->isOfCountry($phone_country)) {
            
            dd($phone, $phone_country, $phone_number);
            //make phone number
            $phone_number = getDatabasePhoneNumber($phone, $phone_country);

            //check if the phone number exixts in db
            $user = User::where('phone', $phone_number)->first();

            if ($user) {
            	//phone already exists
            	return false;
            }

            //phone doesnt exist in db
            return true;

        }

        //error, wrong phone format
        return false;

    }

}