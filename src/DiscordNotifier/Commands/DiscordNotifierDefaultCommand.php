<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\DiscordNotifier\Commands;

use Illuminate\Console\Command;
use Netzindianer\FileNotifier\DiscordNotifier\DiscordNotifierDefault;

class DiscordNotifierDefaultCommand extends Command
{
    protected $signature = "
        file-notifier:discord:default
    ";

    protected $description = "Check if there is new content in file and if it is, send discord message with last lines of file";

    public function __construct(
        protected DiscordNotifierDefault $notifier,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        return ($this->notifier)()->value();
    }
}
