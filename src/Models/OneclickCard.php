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

    protected function markSuccess()
    {
        return $this->update([
            'last_time_used_successfully' => now(),
            'success_count' => $this->success_count + 1,
        ]);
    }

    protected function markError()
    {
        return $this->update([
            'last_time_used_with_errors' => now(),
            'error_count' => $this->error_count + 1,
        ]);
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
