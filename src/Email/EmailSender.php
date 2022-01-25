<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\Email;

use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;

class EmailSender
{
    protected array $emails;
    protected ?string $customSubject = null;

    public function __construct(
        protected Mailer $mailer,
    ) {}

    public function __invoke(string $content, string $fileName): void
    {
        $this->mailer->raw($content, function(Message $message) use($fileName) {
            $message
                ->to($this->emails)
                ->subject($this->customSubject ?? "[Email Notifier] $fileName");
        });
    }

    public function emails(array $emails): static
    {
        $this->emails = $emails;
        return $this;
    }

    public function subject(string $subject): static
    {
        $this->customSubject = $subject;
        return $this;
    }

}
