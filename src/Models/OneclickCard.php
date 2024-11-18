<?php

namespace SextaNet\LaravelOneclick\Models;

class OneclickCard extends Model
{
    public function getLastDigitsAttribute(?string $replace_by = '')
    {
        return str()->of($this->card_number)->replace('X', $replace_by);
    }
}
