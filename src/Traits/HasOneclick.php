<?php

namespace SextaNet\LaravelOneclick\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use SextaNet\LaravelOneclick\Exceptions\MissingEmailOneclick;
use SextaNet\LaravelOneclick\Exceptions\MissingUsernameOneclick;
use SextaNet\LaravelOneclick\Facades\LaravelOneclick;
use SextaNet\LaravelOneclick\Models\OneclickCard;

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

    public function oneclick_cards(): MorphMany
    {
        return $this->morphMany(OneclickCard::class, 'oneclickable');
    }

    public function registerCardOneclick()
    {
        put_oneclickable_session($this);

        return LaravelOneclick::registerCard($this->getUsernameOneclick(), $this->getEmailOneclick());
    }
}
