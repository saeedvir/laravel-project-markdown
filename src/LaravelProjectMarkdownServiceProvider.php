<?php

namespace Saeedvir\LaravelProjectMarkdown;

use Illuminate\Support\ServiceProvider;
use Saeedvir\LaravelProjectMarkdown\Commands\ProjectFilesMarkdownCommand;
use Saeedvir\LaravelProjectMarkdown\Commands\ProjectDbMarkdownCommand;

class LaravelProjectMarkdownServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-project-markdown.php', 
            'laravel-project-markdown'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ProjectFilesMarkdownCommand::class,
                ProjectDbMarkdownCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/laravel-project-markdown.php' => config_path('laravel-project-markdown.php'),
        ], 'config');
    }
}
