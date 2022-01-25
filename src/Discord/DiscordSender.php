<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier\Discord;

use Illuminate\Http\Client\Factory as HttpFactory;

class DiscordSender
{
    protected string $webhookId;
    protected string $webhookToken;
    protected array $message;

    public function __construct(
        protected HttpFactory $http,
    ) {}

    public function __invoke(string $content, string $fileName): void
    {
        $response = $this->http
            ->attach('payload_json', json_encode($this->message), null, ['Content-Type' => 'application/json'])
            ->attach('files[0]', $content, $fileName, ['Content-Type' => 'text/plain'])
            ->post("https://discord.com/api/v9/webhooks/{$this->webhookId}/{$this->webhookToken}?wait=true");

        if ($response->failed()) {
            throw new \Exception($response->body(), $response->status());
        }
    }

    public function webhook(string $id, string $token): static
    {
        $this->webhookId = $id;
        $this->webhookToken = $token;
        return $this;
    }

    public function message(array $message): static
    {
        $this->message = $message;
        return $this;
    }
}
