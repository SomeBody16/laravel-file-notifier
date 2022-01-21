<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\EmailNotifier\Commands;

use Illuminate\Console\Command;
use Netzindianer\FileNotifier\EmailNotifier\EmailNotifier;
use Xtompie\Result\Result;

class EmailNotifierCommand extends Command
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
        protected EmailNotifier $notifier,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $result = ($this->notifier)(
            fileName: $this->option('file-name'),
            seconds: (int)$this->option('seconds'),
            lines: (int)$this->option('lines'),
            emails: $this->option('email'),
            subject: $this->hasOption('subject') ? $this->option('subject') : null,
        );

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
