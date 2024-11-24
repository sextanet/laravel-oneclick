<?php

namespace SextaNet\LaravelOneclick\Models;

class OneclickTransaction extends Model
{
    protected $casts = [
        'details' => 'array',
    ];

    protected $dates = [
        'expiration_at',
        'transaction_at',
    ];
}
