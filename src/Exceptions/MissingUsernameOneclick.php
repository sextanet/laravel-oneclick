<?php

namespace SextaNet\LaravelOneclick\Exceptions;

use Exception;

class MissingUsernameOneclick extends Exception
{
    public $message = 'You need to specify an username to register a card';
}
