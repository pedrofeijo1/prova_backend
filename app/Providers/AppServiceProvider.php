<?php

namespace App\Providers;

use App\Http\Resources\CovenantCollection;
use App\Http\Resources\InstitutionCollection;
use Illuminate\Support\ServiceProvider;

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
        InstitutionCollection::withoutWrapping();
        CovenantCollection::withoutWrapping();
    }
}
