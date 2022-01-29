<?php

namespace App\Package\Menus\src;

use Illuminate\Support\ServiceProvider;

class MenusServiceProvider extends ServiceProvider {
    public function register() {

    }

    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'Menus');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/public' => public_path(''),
        ], 'public');
    }
}
