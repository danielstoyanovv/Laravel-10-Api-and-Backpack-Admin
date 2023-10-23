<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\OauthTokenAdapterService;
use App\Interfaces\ApiTokenServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\PostService;
use App\Interfaces\PostServiceInterface;
use App\Services\UserService;
use App\Interfaces\UserServiceInterface;

class ServiceServiceProvider extends ServiceProvider
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
        $this->app->bind(
            ApiTokenServiceInterface::class,
            OauthTokenAdapterService::class
        );

        $this->app->bind(
            PostServiceInterface::class,
            PostService::class
        );

        $this->app->bind(
            UserServiceInterface::class,
            UserService::class
        );
    }
}
