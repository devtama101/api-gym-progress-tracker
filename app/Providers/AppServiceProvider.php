<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Source - https://stackoverflow.com/a
        // Posted by Amitesh Bharti, modified by community. See post 'Timeline' for change history
        // Retrieved 2025-11-15, License - CC BY-SA 4.0
        $this->configureUrl();
    }

    private function configureUrl(): void
    {
        URL::forceScheme('https');
    }
}
