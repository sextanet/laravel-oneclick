<?php

namespace SextaNet\LaravelOneclick\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SextaNet\LaravelOneclick\LaravelOneclick
 */
class LaravelOneclick extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \SextaNet\LaravelOneclick\LaravelOneclick::class;
    }
}
