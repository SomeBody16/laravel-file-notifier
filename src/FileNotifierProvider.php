<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier;

use Illuminate\Support\ServiceProvider;
use Netzindianer\FileNotifier\DiscordNotifier\Commands\DiscordNotifierCommand;
use Netzindianer\FileNotifier\DiscordNotifier\Commands\DiscordNotifierDefaultCommand;
use Netzindianer\FileNotifier\EmailNotifier\Commands\EmailNotifierCommand;
use Netzindianer\FileNotifier\EmailNotifier\Commands\EmailNotifierDefaultCommand;

class FileNotifierProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/file-notifier.php' => config_path('file-notifier.php')
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                EmailNotifierCommand::class,
                EmailNotifierDefaultCommand::class,
                DiscordNotifierCommand::class,
                DiscordNotifierDefaultCommand::class,
            ]);
        }
    }
}
