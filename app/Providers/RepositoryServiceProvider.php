<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Repository\Contracts\UserRepositoryContract::class, function () {
            $repository = new \App\Repository\Eloquent\UserRepository(new \App\Models\User());
            return new \App\Repository\CacheDecorator\UserRepositoryCache($repository);
        });
        
        $this->app->singleton(\App\Repository\Contracts\RoleRepositoryContract::class, function () {
            $repository = new \App\Repository\Eloquent\RoleRepository(new \App\Models\Role());
            return new \App\Repository\CacheDecorator\RoleRepositoryCache($repository);
        });
    }
}
