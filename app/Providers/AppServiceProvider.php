<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// TOP of AppServiceProvider.php
use Illuminate\Support\Facades\Schema;


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
  

public function boot(): void
{
    Schema::defaultStringLength(191);
}

}
