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

    protected function putOneclickableSession(): void
    {
        session()->put('oneclickable', [
            'model' => self::class,
            'id' => $this->id,
        ]);
    }

    public function registerCardOneclick()
    {
        $this->putOneclickableSession();

        return LaravelOneclick::registerCard($this->getUsernameOneclick(), $this->getEmailOneclick());
    }
}
