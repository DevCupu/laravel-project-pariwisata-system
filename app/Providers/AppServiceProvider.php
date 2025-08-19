<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Destinasi;

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
        // 
        View::composer('layouts.admin', function ($view) {
            $jumlahDestinasi = Destinasi::count();
            $view->with('jumlahDestinasi', $jumlahDestinasi);
        });
    }
}
