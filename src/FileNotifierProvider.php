<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier;

use Illuminate\Support\ServiceProvider;
use Netzindianer\FileNotifier\Discord\DiscordCommand;
use Netzindianer\FileNotifier\Email\EmailCommand;

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
                FileNotifierDefaultCommand::class,
                EmailCommand::class,
                DiscordCommand::class,
            ]);
        }
    }
}
