<?php

namespace App\Providers;

use App\Http\Repositories\TweetRepository\TweetRepositoryInterface;
use App\Http\Repositories\UserRepository\UserRepository;
use App\Http\Repositories\UserRepository\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use TweetRepository;

class RepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TweetRepositoryInterface::class, TweetRepository::class);

    }
}
