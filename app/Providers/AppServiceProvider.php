<?php

namespace App\Providers;

// use App\Repositories\RepositoryInterface;

use App\Repositories\CategoryRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\GroupRepository;
use App\Repositories\UserRepository;
use App\Services\CategoryService;
use App\Services\DeviceService;
use App\Services\GroupService;
use App\Services\UserService;
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
        // $this->app->bind(UserRepository::class, UserRepository::class);
        // $this->app->bind(UserService::class);

        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(UserRepository::class));
        });
        
        $this->app->bind(DeviceService::class, function ($app) {
            return new DeviceService($app->make(DeviceRepository::class));
        });

        $this->app->bind(GroupService::class, function ($app) {
            return new GroupService($app->make(GroupRepository::class));
        });
        $this->app->bind(CategoryService::class, function ($app) {
            return new CategoryService($app->make(CategoryRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
