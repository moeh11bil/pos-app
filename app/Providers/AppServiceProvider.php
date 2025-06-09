<?php

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
    //    Livewire::listen('component.hydrate', function ($component) {
    //         if (method_exists($component, 'refreshProductIndex')) {
    //             $component->dispatch('refresh-product-index');
    //         }
    //     });

        // Blade::component('line-chart', LineChart::class);
    }
}
