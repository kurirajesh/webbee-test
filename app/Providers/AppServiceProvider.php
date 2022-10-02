<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        /*DB::listen(function ($query) {
            Log::error($query);
        });*/
        if (App::environment('local')) {
            DB::listen(function ($query) {
                Log::error(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        }
    }
}
