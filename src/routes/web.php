<?php

use Illuminate\Support\Facades\Route;
use SextaNet\LaravelOneclick\Facades\LaravelOneclick;
use Transbank\Webpay\Oneclick\MallInscription;

Route::get('response', function () {
    return LaravelOneclick::getResultRegisterCard();
})->name('response_url');
