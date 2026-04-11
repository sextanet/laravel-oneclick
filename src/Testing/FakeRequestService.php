<?php

namespace SextaNet\LaravelOneclick\Testing;

use Psr\Http\Message\ResponseInterface;
use Transbank\Contracts\RequestService;
use Transbank\Utils\TransbankApiRequest;
use Transbank\Webpay\Options;

class FakeRequestService implements RequestService
{
    protected array $queue = [];

    protected ?TransbankApiRequest $lastRequest = null;

    public function __construct(array $responses = [])
    {
        $this->queue = $responses;
    }

    public function queue(array $response): self
    {
        $this->queue[] = $response;

        return $this;
    }

    public function request(string $method, string $endpoint, array $payload, Options $options): array
    {
        if (empty($this->queue)) {
            throw new \RuntimeException(
                "LaravelOneclick fake has no more queued responses. Endpoint called: [{$method}] {$endpoint}"
            );
        }

        $this->lastRequest = new TransbankApiRequest(
            $method,
            $options->getApiBaseUrl(),
            $endpoint,
            $payload,
            $options->getHeaders()
        );

        return array_shift($this->queue);
    }

    public function getLastResponse(): ?ResponseInterface
    {
        return null;
    }

    public function getLastRequest(): ?TransbankApiRequest
    {
        return $this->lastRequest;
    }

    public function pendingCount(): int
    {
        return count($this->queue);
    }
}
