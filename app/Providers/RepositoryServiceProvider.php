<?php

namespace App\Providers;

use App\Http\Repositories\Attachment\AttachmentRepository;
use App\Http\Repositories\Attachment\AttachmentRepositoryInterface;
use App\Http\Repositories\FollowRepository\FollowRepository;
use App\Http\Repositories\FollowRepository\FollowRepositoryInterface;
use App\Http\Repositories\TweetRepository\TweetRepository;
use App\Http\Repositories\TweetRepository\TweetRepositoryInterface;
use App\Http\Repositories\UserRepository\UserRepository;
use App\Http\Repositories\UserRepository\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(AttachmentRepositoryInterface::class, AttachmentRepository::class);
        $this->app->bind(FollowRepositoryInterface::class, FollowRepository::class);
    }
}
