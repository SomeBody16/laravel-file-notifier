<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\DiscordNotifier;

use Illuminate\Config\Repository;
use Xtompie\Result\Result;

class DiscordNotifierDefault
{
    public function __construct(
        protected DiscordNotifier $notifier,
        protected DiscordNotifierSender $sender,
        protected Repository $config,
    ) {}

    public function __invoke(): Result
    {
        return ($this->notifier)(
            fileName: $this->config->get("file-notifier.fileName"),
            seconds: $this->config->get('file-notifier.seconds'),
            lines: $this->config->get('file-notifier.lines'),
            webhookId: $this->config->get('file-notifier.discord.webhook.id'),
            webhookToken: $this->config->get('file-notifier.discord.webhook.token'),
            message: $this->config->get('file-notifier.discord.message'),
        );
    }

}
