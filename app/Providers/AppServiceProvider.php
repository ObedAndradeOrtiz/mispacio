<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('sucursal-id', function () {
            return new class {
                protected $id;

                public function set($id)
                {
                    $this->id = $id;
                }

                public function get()
                {
                    return $this->id;
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
