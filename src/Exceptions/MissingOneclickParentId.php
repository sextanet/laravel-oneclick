<?php

namespace SextaNet\LaravelOneclick\Exceptions;

use Exception;

class MissingOneclickParentId extends Exception
{
    public $message = 'You need to specify an id to pay with Oneclick';
}
