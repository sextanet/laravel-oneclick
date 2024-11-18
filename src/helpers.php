<?php

use Illuminate\Database\Eloquent\Model;

function put_oneclickable_session(Model $model): void
{
    session()->put('oneclickable', $model);
}

function get_oneclickable_session(): Model
{
    return session()->get('oneclickable');
}
