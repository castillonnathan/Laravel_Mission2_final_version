<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Mouvement;
use App\Observers\MouvementObserver;


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
        Mouvement::observe(MouvementObserver::class);
    }

}
