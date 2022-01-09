<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
 use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        config([
            'laravellocalization.supportedLocales' => [
                'en'  => array( 'name' => 'English', 'script' => 'Latn', 'native' => 'English' ),

                'ar' => array( 'name' => 'Arabic', 'script' => 'arab', 'native' => 'Arabic' ),


            ],

            'laravellocalization.useAcceptLanguageHeader' => true,

            'laravellocalization.hideDefaultLocaleInURL' => true
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
