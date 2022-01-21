<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\EmailNotifier\Commands;

use Illuminate\Console\Command;
use Netzindianer\FileNotifier\EmailNotifier\EmailNotifierDefault;
use Xtompie\Result\Result;

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
        $result = ($this->notifier)();

        $result->ifFail(function(Result $result) {
            $this->error("Error while sending mail");
            dump($result->errors()->toArray());
        });

        $result->ifSuccess(function() {
            $this->info("Email successfully sent to recipients");
        });

        return 0;
    }
}
