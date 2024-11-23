<?php

namespace SextaNet\LaravelOneclick\Exceptions;

use Exception;

class MissingEmailOneclick extends Exception
{
    public $message = 'You need to specify an email to register a card';
}
