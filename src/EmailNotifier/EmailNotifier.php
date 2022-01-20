<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\EmailNotifier;

use Netzindianer\FileNotifier\FileNotifier;
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
        ?string $customSubject = null,
    ): Result
    {
        return ($this->fileNotifier)(
            fileName: $fileName,
            seconds: $seconds,
            sender: $this->sender
                ->emails($emails)
                ->customSubject($customSubject),
            lines: $lines,
        );
    }

}
