<?php

namespace SextaNet\LaravelOneclick\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use SextaNet\LaravelOneclick\Facades\LaravelOneclick;

class OneclickCard extends Model
{
    use HasUuids;

    public function getLastDigitsAttribute(?string $replace_by = '')
    {
        return str()->of($this->card_number)->replace('X', $replace_by);
    }

    public function pay($parent_buy_order, $details)
    {
        return LaravelOneclick::pay(
            $this->username,
            $this->tbk_user,
            $parent_buy_order,
            $details
        );
    }
}
