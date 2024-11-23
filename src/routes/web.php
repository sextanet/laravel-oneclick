<?php

use Illuminate\Support\Facades\Route;
use SextaNet\LaravelOneclick\Facades\LaravelOneclick;

Route::get('response', function () {
    return LaravelOneclick::getResultRegisterCard();
})->name('response_url');
