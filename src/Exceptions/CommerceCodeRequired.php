<?php

namespace SextaNet\LaravelOneclick\Exceptions;

use Exception;

class CommerceCodeRequired extends Exception
{
    public function __construct($message)
    {
        $this->message = str()->of($message)->remove([
            'API Response:', '- An error has happened on the request'
        ]);
    }
}
