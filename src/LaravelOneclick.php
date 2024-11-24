<?php

namespace SextaNet\LaravelOneclick;

use Illuminate\View\View;
use SextaNet\LaravelOneclick\Exceptions\CommerceCodeRequired;
use SextaNet\LaravelOneclick\Exceptions\MissingKeysInProduction;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionAuthorizeException;
use Transbank\Webpay\Oneclick\MallInscription;
use Transbank\Webpay\Oneclick\MallTransaction;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionAuthorizeResponse;

class LaravelOneclick
{
    public static function instance()
    {
        $instance = new MallInscription;

        if (! config('oneclick.in_production')) {
            return $instance;
        }

        self::checkConfig();

        return $instance->configureForProduction(
            config('oneclick.commerce_code'),
            config('oneclick.secret_key')
        );
    }

    protected static function checkConfig(): void
    {
        if (! config('oneclick.commerce_code') || ! config('oneclick.secret')) {
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
            return dd('rejected', $response);
        }

        if (self::inscriptionIsCancelled($response)) {
            return dd('cancelled', $response);
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
        $instance = new MallTransaction;

        return $instance;
    }

    public static function pay(string $username, string $tbk_user, string $parent_buy_order, array $details): MallTransactionAuthorizeResponse
    {
        try {
            $response = self::transactionInstance()
                ->authorize(
                    $username,
                    $tbk_user,
                    $parent_buy_order,
                    $details
                );

            return $response;
        } catch (MallTransactionAuthorizeException $e) {
            throw new CommerceCodeRequired($e->getMessage());
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
