<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\DiscordNotifier;

use Netzindianer\FileNotifier\FileNotifier;
use Xtompie\Result\Result;

class DiscordNotifier
{
    public function __construct(
        protected FileNotifier $fileNotifier,
        protected DiscordNotifierSender $sender,
    ) {}

    public function __invoke(
        string $fileName,
        int $seconds,
        int $lines,
        string $webhookId,
        string $webhookToken,
        array $message,
    ): Result
    {
        return ($this->fileNotifier)(
            fileName: $fileName,
            seconds: $seconds,
            sender: $this->sender
                ->webhook($webhookId, $webhookToken)
                ->message($message),
            lines: $lines,
        );
    }
}
