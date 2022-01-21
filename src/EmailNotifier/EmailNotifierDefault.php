<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\EmailNotifier;

use Illuminate\Config\Repository;
use Xtompie\Result\Result;

class EmailNotifierDefault
{
    public function __construct(
        protected EmailNotifier $notifier,
        protected EmailNotifierSender $sender,
        protected Repository $config,
    ) {}

    public function __invoke(): Result
    {
        return ($this->notifier)(
            fileName: $this->config->get("file-notifier.fileName"),
            seconds: $this->config->get('file-notifier.seconds'),
            lines: $this->config->get('file-notifier.lines'),
            emails: $this->config->get('file-notifier.email.emails'),
            subject: $this->config->get('file-notifier.email.subject'),
        );
    }

}
