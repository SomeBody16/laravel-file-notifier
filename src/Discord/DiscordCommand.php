<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\Discord;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Netzindianer\FileNotifier\FileNotifier;
use Xtompie\Result\Result;

class DiscordCommand extends Command
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
        protected FileNotifier $notifier,
        protected DiscordSender $sender,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $result = ($this->notifier)(
            fileName: $this->option('file-name'),
            seconds: (int)$this->option('seconds'),
            sender: $this->sender
                ->webhook(
                    id: $this->option('webhook-id'),
                    token: $this->option('webhook-token'),
                )
                ->message($this->option('message')),
            lines: (int)$this->option('lines'),
        );

        $result->ifFail(function(Result $result) {
            $this->error($result->errors()->first()->message());
        });

        $result->ifSuccess(function(Result $result) {
            if ($result->value() === -1) {
                $this->info("File is empty, skipping...");
            } else {
                $this->info("Message successfully sent to channel");
            }
        });

        return 0;
    }

    public function info($string, $verbosity = null)
    {
        $now = Carbon::now()->toDateTimeString();
        parent::info("[$now] $string", $verbosity);
    }

    public function error($string, $verbosity = null)
    {
        $now = Carbon::now()->toDateTimeString();
        parent::error("[$now] $string", $verbosity);
    }
}
