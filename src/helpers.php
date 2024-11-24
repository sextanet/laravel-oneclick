<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

function put_oneclickable_session(Model $model): void
{
    session()->put('oneclickable', $model);
}

function get_oneclickable_session(): Model
{
    return session()->get('oneclickable');
}

function put_oneclick_user_session(string $username): void
{
    session()->put('oneclick_user', $username);
}

function get_oneclick_user_session(): string
{
    return session()->get('oneclick_user');
}
