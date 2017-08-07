<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Home
        $this->app->bind(
            \App\Repositories\Home\HomeRepositoryInterface::class,
            \App\Repositories\Home\HomeRepository::class
        );

        // Add
        $this->app->bind(
            \App\Repositories\Add\AddRepositoryInterface::class,
            \App\Repositories\Add\AddRepository::class
        );
    }
}
