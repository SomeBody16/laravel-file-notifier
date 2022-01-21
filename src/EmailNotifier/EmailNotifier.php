<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\EmailNotifier;

use Netzindianer\FileNotifier\FileNotifier;
use Netzindianer\FileNotifier\FileNotifierLog;
use Xtompie\Result\Result;

class EmailNotifier
{
    public function __construct(
        protected FileNotifier $fileNotifier,
        protected EmailNotifierSender $sender,
    ) {}

    public function __invoke(
        string $fileName,
        int $seconds,
        int $lines,
        array $emails,
        ?string $subject = null,
    ): Result
    {
        FileNotifierLog::debug("EmailNotifier::__invoke", ['emails' => $emails, 'subject' => $subject]);
        return ($this->fileNotifier)(
            fileName: $fileName,
            seconds: $seconds,
            sender: $this->sender
                ->emails($emails)
                ->subject($subject),
            lines: $lines,
        );
    }

}
