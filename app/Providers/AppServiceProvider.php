<?php

namespace App\Providers;

use App\Http\Controllers\HelperController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\DeviceType;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        View::share('types', DeviceType::orderBy('import_ref')->get());
        View::share('deviceTypes',HelperController::getDeviceListForNavigationMenu());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
