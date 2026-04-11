<?php

use PHPUnit\Framework\AssertionFailedError;
use SextaNet\LaravelOneclick\LaravelOneclick;
use SextaNet\LaravelOneclick\Testing\FakeRequestService;
use SextaNet\LaravelOneclick\Testing\LaravelOneclickFake;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Webpay\Oneclick\Responses\InscriptionStartResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionAuthorizeResponse;

afterEach(fn () => LaravelOneclick::disableTests());

it('returns a LaravelOneclickFake instance from enableTests()', function () {
    $fake = LaravelOneclick::enableTests();

    expect($fake)->toBeInstanceOf(LaravelOneclickFake::class);
});

it('restores normal mode after disableTests()', function () {
    LaravelOneclick::enableTests();
    LaravelOneclick::disableTests();

    $inscription = LaravelOneclick::instance();

    expect($inscription->getRequestService())
        ->not->toBeInstanceOf(FakeRequestService::class);
});

it('returns the stub inscription start response', function () {
    LaravelOneclick::enableTests()->withInscriptionStart();

    $response = LaravelOneclick::instance()
        ->start('test_user', 'test@example.com', 'https://example.com/response');

    expect($response)->toBeInstanceOf(InscriptionStartResponse::class)
        ->and($response->getToken())->toBe('e9d555262db0649505e01938e4b843ee21c92de4813eaed51fefc62d10408b8b')
        ->and($response->getUrlWebpay())->toBe('https://webpay3gint.transbank.cl/webpayserver/initTransaction')
        ->and($response->getRedirectUrl())->toContain($response->getToken());
});

it('allows overriding stub token in inscription start', function () {
    LaravelOneclick::enableTests()->withInscriptionStart([
        'token' => 'custom_token_abc',
    ]);

    $response = LaravelOneclick::instance()
        ->start('test_user', 'test@example.com', 'https://example.com/response');

    expect($response->getToken())->toBe('custom_token_abc');
});

it('returns an approved inscription finish response', function () {
    LaravelOneclick::enableTests()->withInscriptionFinishApproved();

    $response = LaravelOneclick::instance()->finish('any_token');

    expect($response)->toBeInstanceOf(InscriptionFinishResponse::class)
        ->and($response->getResponseCode())->toBe(0)
        ->and($response->isApproved())->toBeTrue()
        ->and($response->getTbkUser())->toBe('b6bd6ba3-e718-4107-9386-d2b099a8dd42')
        ->and($response->getAuthorizationCode())->toBe('123456')
        ->and($response->getCardType())->toBe('Visa')
        ->and($response->getCardNumber())->toBe('XXXXXXXXXXXX6623');
});

it('returns a rejected inscription finish response', function () {
    LaravelOneclick::enableTests()->withInscriptionFinishRejected();

    $response = LaravelOneclick::instance()->finish('any_token');

    expect($response)->toBeInstanceOf(InscriptionFinishResponse::class)
        ->and($response->getResponseCode())->toBe(-1)
        ->and($response->isApproved())->toBeFalse()
        ->and($response->getTbkUser())->toBeNull();
});

it('returns a cancelled inscription finish response', function () {
    LaravelOneclick::enableTests()->withInscriptionFinishCancelled();

    $response = LaravelOneclick::instance()->finish('any_token');

    expect($response)->toBeInstanceOf(InscriptionFinishResponse::class)
        ->and($response->getResponseCode())->toBe(-96)
        ->and($response->isApproved())->toBeFalse()
        ->and($response->getTbkUser())->toBeNull();
});

it('returns an approved transaction authorize response via LaravelOneclick::pay()', function () {
    LaravelOneclick::enableTests()->withTransactionAuthorizeApproved();

    $response = LaravelOneclick::pay(
        username: 'test_user',
        tbk_user: 'b6bd6ba3-e718-4107-9386-d2b099a8dd42',
        parent_buy_order: 'testapp-loca-001',
        details: [
            [
                'amount' => 1000,
                'buy_order' => 'order-detail-001',
                'installments_number' => 0,
                'commerce_code' => '597055555542',
            ],
        ]
    );

    expect($response)->toBeInstanceOf(MallTransactionAuthorizeResponse::class)
        ->and($response->isApproved())->toBeTrue()
        ->and($response->getBuyOrder())->toBe('testapp-loca-001')
        ->and($response->getCardNumber())->toBe('6623')
        ->and($response->getDetails())->toHaveCount(1)
        ->and($response->getDetails()[0]->status)->toBe('AUTHORIZED')
        ->and($response->getDetails()[0]->responseCode)->toBe(0)
        ->and($response->getDetails()[0]->amount)->toBe(1000);
});

it('returns a rejected transaction authorize response via LaravelOneclick::pay()', function () {
    LaravelOneclick::enableTests()->withTransactionAuthorizeRejected();

    $response = LaravelOneclick::pay(
        username: 'test_user',
        tbk_user: 'b6bd6ba3-e718-4107-9386-d2b099a8dd42',
        parent_buy_order: 'testapp-loca-001',
        details: [
            [
                'amount' => 1000,
                'buy_order' => 'order-detail-001',
                'installments_number' => 0,
                'commerce_code' => '597055555542',
            ],
        ]
    );

    expect($response)->toBeInstanceOf(MallTransactionAuthorizeResponse::class)
        ->and($response->isApproved())->toBeFalse()
        ->and($response->getDetails()[0]->status)->toBe('FAILED')
        ->and($response->getDetails()[0]->responseCode)->toBe(-1);
});

it('allows overriding the authorized amount in the transaction stub', function () {
    LaravelOneclick::enableTests()->withTransactionAuthorizeApproved([
        'details' => [
            ['amount' => 9999],
        ],
    ]);

    $response = LaravelOneclick::pay('u', 'tbk', 'order-001', []);

    expect($response->getDetails()[0]->amount)->toBe(9999);
});

it('processes stubs in the order they were queued', function () {
    LaravelOneclick::enableTests()
        ->withInscriptionStart()
        ->withInscriptionFinishApproved();

    $start = LaravelOneclick::instance()
        ->start('u', 'u@test.com', 'https://example.com');

    $finish = LaravelOneclick::instance()
        ->finish($start->getToken());

    expect($start->getToken())->not->toBeEmpty()
        ->and($finish->isApproved())->toBeTrue();
});

it('raises an error when no stub is queued for a call', function () {
    LaravelOneclick::enableTests();

    expect(fn () => LaravelOneclick::instance()->start('u', 'u@test.com', 'https://example.com'))
        ->toThrow(RuntimeException::class, 'LaravelOneclick fake has no more queued responses');
});

it('passes assertAllResponsesConsumed when all stubs were used', function () {
    $fake = LaravelOneclick::enableTests()
        ->withInscriptionStart();

    LaravelOneclick::instance()->start('u', 'u@test.com', 'https://example.com');

    $fake->assertAllResponsesConsumed();
});

it('fails assertAllResponsesConsumed when stubs remain unused', function () {
    $fake = LaravelOneclick::enableTests()
        ->withInscriptionStart()
        ->withInscriptionFinishApproved();

    LaravelOneclick::instance()->start('u', 'u@test.com', 'https://example.com');

    expect(fn () => $fake->assertAllResponsesConsumed())
        ->toThrow(AssertionFailedError::class);
});
