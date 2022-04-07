<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Classes\Smsc;

class SmscServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('smsc', Smsc::class);
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
