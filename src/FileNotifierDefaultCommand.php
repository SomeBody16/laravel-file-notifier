<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Console\Command;
use Netzindianer\FileNotifier\Discord\DiscordSender;
use Netzindianer\FileNotifier\Email\EmailSender;
use Xtompie\Result\Result;

class FileNotifierDefaultCommand extends Command
{
    protected $signature = "file-notifier:default";

    protected $description = "Check if there is new content in file and if it is, send message with senders configured in config";

    public function __construct(
        protected ConfigRepository $config,
        protected FileNotifier $notifier,
        protected EmailSender $emailSender,
        protected DiscordSender $discordSender,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $senders = [];

        if ($emailConfig = $this->getConfigValue('email'))
        {
            $senders['email'] = $this->emailSender
                ->emails($emailConfig['emails'])
                ->subject($emailConfig['subject']);
        }

        if ($discordConfig = $this->getConfigValue('discord'))
        {
            $senders['discord'] = $this->discordSender
                ->webhook($discordConfig['webhook']['id'], $discordConfig['webhook']['token'])
                ->message($discordConfig['message']);
        }

        if (!count($senders)) {
            $this->error("No senders were configured. Check your config/file-notifier.php file");
            return 0;
        }

        foreach ($senders as $name => $sender)
        {
            $result = ($this->notifier)(
                fileName: $this->getConfigValue('fileName'),
                seconds: $this->getConfigValue('seconds'),
                sender: $sender,
                lines: $this->getConfigValue('lines'),
            );

            $result->ifFail(function(Result $result) use($name) {
                $this->error($name . " : " . $result->errors()->first()->message());
            });

            $result->ifSuccess(function() use($name) {
                $this->info("Sender '$name' succeed");
            });
        }

        return 0;
    }

    protected function getConfigValue(string $key): mixed
    {
        return $this->config->get("file-notifier.$key");
    }
}
