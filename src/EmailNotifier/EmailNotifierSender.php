<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\EmailNotifier;

use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;

class EmailNotifierSender
{
    protected array $emails;
    protected ?string $customSubject = null;

    public function __construct(
        protected Mailer $mailer,
    ) {}

    public function __invoke(string $content, string $fileName): int
    {
        $this->mailer->raw($content, function(Message $message) use($fileName) {
            $message
                ->to($this->emails)
                ->subject($this->customSubject ?? "[Email Notifier] $fileName");
        });
        return 0;
    }

    public function emails(array $emails): static
    {
        $this->emails = $emails;
        return $this;
    }

    public function customSubject(string $customSubject): static
    {
        $this->customSubject = $customSubject;
        return $this;
    }

}
