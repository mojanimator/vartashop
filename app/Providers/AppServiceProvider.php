<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
//        Validator::extend('recaptcha', 'App\\Rules\\ReCaptcha@validate');
        Schema::defaultStringLength(191); //NEW: Increase StringLength
//        $this->app->bind('path.public', function () {
//            return base_path() . '/../public_html';
//        });
        Validator::extend('base64_image', function ($attribute, $value, $parameters, $validator) {
            return validate_base64($value, ['png', 'jpg', 'jpeg']);
        });
        Validator::extend('base64_size', function ($attribute, $value, $parameters, $validator) {
            return strlen(base64_decode($value)) / 1024 < $parameters[0];

        });
    }
}
