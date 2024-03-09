<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */


    public function boot()
    {
        Validator::extend('regexEmail', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/', $value);
        });
    
        Validator::extend('regexPhone', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})\b/', $value);
        });
    
        Validator::extend('regexUserName', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9_-]{3,16}$/', $value);
        });
    
        Validator::extend('regexPassword', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-zA-Z0-9!@#$%^&*()_+-=<>?]{6,18}$/', $value);
        });
    
        Validator::extend('minimumLetters', function ($attribute, $value, $parameters, $validator) {
            $lettersCount = strlen(preg_replace('/\s+/', '', $value));
            return $lettersCount >= 1;
        });
    
        Validator::extend('selectRequired', function ($attribute, $value, $parameters, $validator) {
            return $value !== '--Choose a Role--';
        });
    }
}
