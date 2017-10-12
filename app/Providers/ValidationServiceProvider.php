<?php

namespace App\Providers;

use App\Validators\CustomRule;
use Illuminate\Support\ServiceProvider;

use Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
        $this->uniquePhoneValidator();

    }

    /*private function uniquePhoneValidator()
    {
        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomRule($translator, $data, $rules, $messages);
        });

        Validator::extend('unique_phone', function($attribute, $value, $parameters, $validator) {
            if(!empty($value) && (strlen($value) % 2) == 0){
                return true;
            }
                return false;
        });

    }
*/
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
