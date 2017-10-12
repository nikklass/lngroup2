<?php

namespace App\Providers;

use App\Validators\CustomRule;
use Illuminate\Support\ServiceProvider;

use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //unique phone validator
        $this->uniquePhoneValidator();
    }

    private function uniquePhoneValidator()
    {
        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomRule($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
