<?php

namespace SextaNet\LaravelOneclick\Testing;

use PHPUnit\Framework\Assert;

class LaravelOneclickFake
{
    public function __construct(
        protected FakeRequestService $inscriptionService,
        protected FakeRequestService $transactionService,
    ) {}

    public function withInscriptionStart(array $overrides = []): static
    {
        $this->inscriptionService->queue(
            array_merge(static::stub('inscription_start'), $overrides)
        );

        return $this;
    }

    public function withInscriptionFinishApproved(array $overrides = []): static
    {
        $this->inscriptionService->queue(
            array_merge(static::stub('inscription_finish_approved'), $overrides)
        );

        return $this;
    }

    public function withInscriptionFinishRejected(array $overrides = []): static
    {
        $this->inscriptionService->queue(
            array_merge(static::stub('inscription_finish_rejected'), $overrides)
        );

        return $this;
    }

    public function withInscriptionFinishCancelled(array $overrides = []): static
    {
        $this->inscriptionService->queue(
            array_merge(static::stub('inscription_finish_cancelled'), $overrides)
        );

        return $this;
    }

    public function withTransactionAuthorizeApproved(array $overrides = []): static
    {
        $this->transactionService->queue(
            array_replace_recursive(static::stub('transaction_authorize_approved'), $overrides)
        );

        return $this;
    }

    public function withTransactionAuthorizeRejected(array $overrides = []): static
    {
        $this->transactionService->queue(
            array_replace_recursive(static::stub('transaction_authorize_rejected'), $overrides)
        );

        return $this;
    }

    public function getInscriptionService(): FakeRequestService
    {
        return $this->inscriptionService;
    }

    public function getTransactionService(): FakeRequestService
    {
        return $this->transactionService;
    }

    public function assertAllResponsesConsumed(): void
    {
        $inscription = $this->inscriptionService->pendingCount();
        $transaction = $this->transactionService->pendingCount();

        Assert::assertEquals(
            0,
            $inscription + $transaction,
            "LaravelOneclick fake still has {$inscription} inscription and {$transaction} transaction response(s) queued."
        );
    }

    protected static function stub(string $name): array
    {
        $path = __DIR__.'/stubs/'.$name.'.json';

        if (! file_exists($path)) {
            throw new \InvalidArgumentException("Oneclick stub [{$name}] not found at [{$path}].");
        }

        return json_decode(file_get_contents($path), true);
    }
}
