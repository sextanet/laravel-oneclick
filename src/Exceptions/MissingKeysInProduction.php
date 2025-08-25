<?php

namespace SextaNet\LaravelOneclick\Exceptions;

use Exception;

class MissingKeysInProduction extends Exception
{
    public function __construct()
    {
        parent::__construct('API key, Mall code, Commerce code are required when you are in production mode');
    }
}
