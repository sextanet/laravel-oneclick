<?php

namespace SextaNet\LaravelOneclick;

use Illuminate\View\View;
use SextaNet\LaravelOneclick\Exceptions\MissingKeysInProduction;
use Transbank\Webpay\Oneclick\MallInscription;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;

class LaravelOneclick
{
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
        $response = (new MallInscription)
            ->start($username, $email, self::getResponseUrl());

        return view('oneclick::helpers.redirect', [
            'token' => $response->getToken(),
            'url' => $response->getUrlWebpay(),
        ]);
    }

    public static function getResultRegisterCard()
    {
        $response = (new MallInscription)
            ->finish(request('TBK_TOKEN'));

        if (self::inscriptionIsApproved($response)) {
            return dd($response->getTbkUser());
        }

        if (self::inscriptionIsCancelled($response)) {
            return dd($response);
        }
    }

    protected static function inscriptionIsApproved(InscriptionFinishResponse $response): bool
    {
        return $response->getResponseCode() === 0;
    }

    protected static function inscriptionIsCancelled(InscriptionFinishResponse $response): bool
    {
        return $response->getResponseCode() === -96;
    }
}
