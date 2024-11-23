<?php

namespace SextaNet\LaravelOneclick\Exceptions;

use Exception;

class MissingKeysInProduction extends Exception
{
    public function __construct()
    {
        parent::__construct('Commerce code and secret key are required when you are in production mode');
    }
}
