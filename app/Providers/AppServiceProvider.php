<?php

namespace App\Providers;

use App\Services\YoutubeService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(YoutubeService::class, function ($app) {
            return new YoutubeService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ini_set('upload_max_filesize', '50M');
        // ini_set('post_max_size', '50M');

        // Set the maximum execution time to unlimited
        set_time_limit(0); // or set_time_limit(300); for 5 minutes

    }
}
