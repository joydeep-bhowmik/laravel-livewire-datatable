<?php
namespace JoydeepBhowmik\LivewireDatatable\Providers;

use Illuminate\Support\ServiceProvider;

class DataTableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'joydeep-bhowmik/livewire-datatable');
        // Publish CSS file from your package to public directory of the Laravel application
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('joydeep-bhowmik/livewire-datatable/css'),
        ], 'joydeep-bhowmik-livewire-datatable-css');
    }
}
