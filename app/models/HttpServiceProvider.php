<?php

use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider {

    /**
     * Register the binding
     *
     * @return void
     */
    public function register()
    {
        $this->app->middleware( new RateLimiter($this->app) );
    }

}