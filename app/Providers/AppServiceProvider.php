<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);

        /**
         * Force all request to HTTPS
         * @see  https://laravel.com/api/5.6/Illuminate/Routing/UrlGenerator.html#method_forceScheme
         * @author Farhan Naufal G.
         */
        if ((bool) env('HTTPS', false)) {
            $this->app['url']->forceScheme('https');
        }

        $this->app['request']->server->set('HTTPS', env('HTTPS'));

    }
}
