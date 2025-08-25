<?php

namespace SextaNet\LaravelOneclick;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use SextaNet\LaravelOneclick\Exceptions\MissingKeysInProduction;
use SextaNet\LaravelOneclick\Exceptions\UnhandledAPIResponse;
use SextaNet\LaravelOneclick\Models\OneclickCard;
use SextaNet\LaravelOneclick\Models\OneclickTransaction;
use Transbank\Webpay\Oneclick\MallInscription;
use Transbank\Webpay\Oneclick\MallTransaction;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionAuthorizeResponse;

class LaravelOneclick
{
    protected static string $api_key = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';

    protected static string $commerce_code = '597055555541';

    public static function instance(): MallInscription
    {
        return config('oneclick.in_production')
            ? static::createTransactionForProduction()
            : static::createTransactionForIntegration();
    }

    protected static function createTransactionForIntegration(): MallInscription
    {
        return MallInscription::buildForIntegration(static::$api_key, static::$commerce_code);
    }

    protected static function createTransactionForProduction(): MallInscription
    {
        self::checkProductionKeys();

        return MallInscription::buildForProduction(
            config('oneclick.api_key'),
            config('oneclick.commerce_code')
        );
    }

    protected static function checkProductionKeys(): void
    {
        if (! config('oneclick.api_key') || ! config('oneclick.commerce_code') || ! config('oneclick.mall_code')) {
            throw new MissingKeysInProduction;
        }
    }

    protected static function getResponseUrl(): string
    {
        return route('oneclick.response_url');
    }

    protected function getApprovedUrl(): string
    {
        return session()->get('response_url') ?? route('oneclick.approved');
    }

    public static function setApprovedUrl(string $approved_url): void
    {
        session()->flash('approved_url', $approved_url);
    }

    public static function setRejectedUrl(string $rejected_url): void
    {
        session()->flash('rejected_url', $rejected_url);
    }

    public static function setCancelledUrl(string $cancelled_url): void
    {
        session()->flash('cancelled_url', $cancelled_url);
    }

    public static function registerCard(string $username, string $email): View
    {
        put_oneclick_user_session($username);

        $response = self::instance()
            ->start($username, $email, self::getResponseUrl());

        return view('oneclick::helpers.redirect', [
            'token' => $response->getToken(),
            'url' => $response->getUrlWebpay(),
        ]);
    }

    public static function getResultRegisterCard()
    {
        $response = self::instance()
            ->finish(request('TBK_TOKEN'));

        if (self::inscriptionIsApproved($response)) {
            $model = get_oneclickable_session();

            $model->storeCardOneclick(
                get_oneclick_user_session(),
                $response->getTbkUser(),
                $response->getAuthorizationCode(),
                $response->getCardType(),
                $response->getCardNumber()
            );

            // return dd('approved', $response->getTbkUser());

            return session()->get('approved_url')
                ? redirect(session()->get('approved_url'))
                : view('oneclick::responses.approved');
        }

        if (self::inscriptionIsRejected($response)) {
            return session()->get('rejected_url')
                ? redirect(session()->get('rejected_url'))
                : view('oneclick::responses.rejected', compact('response'));
        }

        if (self::inscriptionIsCancelled($response)) {
            return session()->get('cancelled_url')
                ? redirect(session()->get('cancelled_url'))
                : view('oneclick::responses.cancelled', compact('response'));
        }
    }

    protected static function inscriptionIsApproved(InscriptionFinishResponse $response): bool
    {
        return $response->getResponseCode() === 0;
    }

    protected static function inscriptionIsRejected(InscriptionFinishResponse $response): bool
    {
        return $response->getResponseCode() === -1;
    }

    protected static function inscriptionIsCancelled(InscriptionFinishResponse $response): bool
    {
        return $response->getResponseCode() === -96;
    }

    public static function transactionInstance(): MallTransaction
    {
        if (! config('oneclick.in_production')) {
            return new MallTransaction;
        }

        self::checkProductionKeys();

        return (new MallTransaction)->configureForProduction(
            config('oneclick.commerce_code'),
            config('oneclick.api_key')
        );
    }

    public static function pay(string $username, string $tbk_user, string $parent_buy_order, array $details): MallTransactionAuthorizeResponse
    {
        // dd($parent_buy_order);
        try {
            $response = self::transactionInstance()
                ->authorize(
                    $username,
                    $tbk_user,
                    $parent_buy_order,
                    $details
                );

            return $response;
        } catch (\Exception $e) {
            throw new UnhandledAPIResponse($e->getMessage());
        }
    }

    public static function payAndStore(Model $model, OneclickCard $oneclick_card, array $details): OneclickTransaction
    {
        $parent_order = generate_oneclick_parent_id($model->getOneclickParentId());

        $result = $oneclick_card->pay($parent_order, $details);

        $converted = array_merge(
            format_transaction_response($result),
            get_transactable_fields($model)
        );

        return $oneclick_card->transactions()->create($converted);
    }
}
