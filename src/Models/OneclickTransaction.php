<?php

namespace SextaNet\LaravelOneclick\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class OneclickTransaction extends Model
{
    protected $casts = [
        'details' => 'array',
    ];

    protected $dates = [
        'expiration_at',
        'transaction_at',
    ];

    public function transactable(): MorphTo
    {
        return $this->morphTo();
    }
}
