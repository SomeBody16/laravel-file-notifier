<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\DiscordNotifier\Commands;

use Illuminate\Console\Command;
use Netzindianer\FileNotifier\DiscordNotifier\DiscordNotifier;
use Xtompie\Result\Result;

class DiscordNotifierCommand extends Command
{
    protected $signature = "
        file-notifier:discord
        {--file-name= : Path to the file}
        {--seconds= : If modified since this seconds, send emails}
        {--lines= : How many lines of file include in email}
        {--webhook-id= : Webhook id}
        {--webhook-token= : Webhook token}
        {--message= : JSON body of message to sent with file}
    ";

    protected $description = "Check if there is new content in file and if it is, send discord message with last lines of file";

    public function __construct(
        protected DiscordNotifier $notifier,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $result = ($this->notifier)(
            fileName: $this->option('file-name'),
            seconds: (int)$this->option('seconds'),
            lines: (int)$this->option('lines'),
            webhookId: $this->option('webhook-id'),
            webhookToken: $this->option('webhook-token'),
            message: $this->option('message'),
        );

        $result->ifFail(function(Result $result) {
            $this->error("Error while sending mail");
            dump($result->errors()->toArray());
        });

        $result->ifSuccess(function() {
            $this->info("Message successfully sent to recipients");
        });

        return 0;
    }
}
