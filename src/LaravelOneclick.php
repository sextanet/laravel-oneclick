<?php

namespace SextaNet\LaravelOneclick;

use Illuminate\View\View;
use SextaNet\LaravelOneclick\Exceptions\MissingKeysInProduction;
use Transbank\Webpay\Oneclick\MallInscription;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;

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
                $response->getTbkUser(),
                $response->getAuthorizationCode(),
                $response->getCardType(),
                $response->getCardNumber()
            );

            return dd('approved', $response->getTbkUser());
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
}
