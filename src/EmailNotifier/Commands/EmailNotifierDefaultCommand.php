<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\EmailNotifier\Commands;

use Illuminate\Console\Command;
use Netzindianer\FileNotifier\EmailNotifier\EmailNotifierDefault;

class EmailNotifierDefaultCommand extends Command
{
    protected $signature = "
        file-notifier:email:default
    ";

    protected $description = "Check if there is new content in file and if it is, send emails with last lines of file";

    public function __construct(
        protected EmailNotifierDefault $notifier,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        return ($this->notifier)()->value();
    }
}
