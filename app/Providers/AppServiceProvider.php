<?php

namespace Trackit\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;

use Trackit\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Trackit\Models\User', function() {
            return Auth::user() == null ? User::create() : Auth::user();
        });
    }
}
