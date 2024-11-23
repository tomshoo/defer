<?php

namespace Tomshoo\Defer;

use Illuminate\Support\ServiceProvider;

class DeferServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DeferCallbackQueue::class);
    }

    public function boot()
    {
        $this->app->make(DeferCallbackQueue::class);
    }
}
