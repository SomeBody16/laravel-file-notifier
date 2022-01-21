<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\DiscordNotifier\Commands;

use Illuminate\Console\Command;
use Netzindianer\FileNotifier\DiscordNotifier\DiscordNotifierDefault;
use Xtompie\Result\Result;

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
        $result = ($this->notifier)();

        $result->ifFail(function(Result $result) {
            $this->error($result->errors()->first()->message());
        });

        $result->ifSuccess(function() {
            $this->info("Email successfully sent to recipients");
        });

        return 0;
    }
}
