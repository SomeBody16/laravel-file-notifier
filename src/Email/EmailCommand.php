<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\Email;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Netzindianer\FileNotifier\FileNotifier;
use Xtompie\Result\Result;

class EmailCommand extends Command
{
    protected $signature = "
        file-notifier:email
        {--file-name= : Path to the file}
        {--seconds= : If modified since this seconds, send emails}
        {--lines= : How many lines of file include in email}
        {--email=* : Emails to which will be send logs}
        {--subject=? : Subject of email message}
    ";

    protected $description = "Check if there is new content in file and if it is, send emails with last lines of file";

    public function __construct(
        protected FileNotifier $notifier,
        protected EmailSender $sender,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $result = ($this->notifier)(
            fileName: $this->option('file-name'),
            seconds: (int)$this->option('seconds'),
            sender: $this->sender
                ->emails($this->option('email'))
                ->subject($this->hasOption('subject') ? $this->option('subject') : null),
            lines: (int)$this->option('lines'),
        );

        $result->ifFail(function(Result $result) {
            $this->error($result->errors()->first()->message());
        });

        $result->ifSuccess(function(Result $result) {
            if ($result->value() === -1) {
                $this->info("File is empty, skipping...");
            } else {
                $this->info("Email successfully sent to recipients");
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
