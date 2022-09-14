<?php

namespace App\Providers;

use App\Interfaces\CommentInterface\CommentInterface;
use App\Interfaces\PostInterface\PostInterface;
use App\Interfaces\SkillsInterface\SkillsInterface;
use App\Interfaces\UserInterface\UserInterface;
use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;
use App\Repositories\SkillsRepository;
use App\Repositories\UserRepository;
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
        $this->app->bind(
            UserInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            SkillsInterface::class,
            SkillsRepository::class
        );
        $this->app->bind(
            PostInterface::class,
            PostRepository::class
        );
        $this->app->bind(
            CommentInterface::class,
            CommentRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
