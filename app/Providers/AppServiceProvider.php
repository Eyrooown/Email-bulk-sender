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
        // Ensure generated URLs (including Livewire upload endpoints) match the
        // current request host. This prevents cross-origin upload failures when
        // APP_URL differs from the domain you opened in the browser (localhost vs .test).
        if (!$this->app->runningInConsole()) {
            try {
                $root = request()->getSchemeAndHttpHost();
                if ($root) {
                    config(['app.url' => $root]);
                    URL::forceRootUrl($root);
                }
            } catch (\Throwable $e) {
                // Ignore if request() isn't available for some reason.
            }
        }
    }
}
