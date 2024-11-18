<?php

use Illuminate\Support\Facades\Route;

Route::get('response', function () {
    return view('oneclick::responses.default');
})->name('response_url');
