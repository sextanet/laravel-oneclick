<?php

namespace SextaNet\LaravelOneclick\Enums;

class ResponseStatus
{
    const SUCCESS = 'success';

    const FAILED = 'failed';

    public static function getName($status)
    {
        return match ($status) {
            self::SUCCESS => 'success',
            self::FAILED => 'failed',
            default => 'unknown',
        };
    }
}
