<?php

namespace SextaNet\LaravelOneclick\Traits;

use SextaNet\LaravelOneclick\Exceptions\MissingEmailOneclick;
use SextaNet\LaravelOneclick\Exceptions\MissingUsernameOneclick;
use SextaNet\LaravelOneclick\Facades\LaravelOneclick;

trait HasOneclick
{
    public function getUsernameOneclick()
    {
        return $this->username ?? throw new MissingUsernameOneclick;
    }

    public function getEmailOneclick()
    {
        return $this->email ?? throw new MissingEmailOneclick;
    }

    public function registerCardOneclick()
    {
        return LaravelOneclick::registerCard($this->getUsernameOneclick(), $this->getEmailOneclick());
    }
}
