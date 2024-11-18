<?php

namespace SextaNet\LaravelOneclick;

use SextaNet\LaravelOneclick\Exceptions\MissingKeysInProduction;
use Transbank\Webpay\Oneclick\MallInscription;

class LaravelOneclick {
    protected static function checkConfig(): void
    {
        if (! config('oneclick.commerce_code') || ! config('oneclick.secret')) {
            throw new MissingKeysInProduction;
        }
    }

    protected static function getResponseUrl(): string
    {
        return session()->get('response_url') ?? route('oneclick.response');
    }

    public static function setResponseUrl(string $response_url): void
    {
        session()->flash('response_url', $response_url);
    }

    public static function registerCard(string $username, string $email)
    {
        $response = (new MallInscription)
            ->start($username, $email, self::getResponseUrl());

        return view('oneclick::helpers.redirect', [
            'token' => $response->getToken(),
            'url' => $response->getUrlWebpay(),
        ]);
    }
}
