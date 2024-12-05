<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use SextaNet\LaravelOneclick\Enums\ResponseStatus;

if (! function_exists('put_oneclickable_session')) {
    function put_oneclickable_session(Model $model): void
    {
        session()->put('oneclickable', $model);
    }
}

if (! function_exists('get_oneclickable_session')) {
    function get_oneclickable_session(): Model
    {
        return session()->get('oneclickable');
    }
}

if (! function_exists('put_oneclick_user_session')) {
    function put_oneclick_user_session(string $username): void
    {
        session()->put('oneclick_user', $username);
    }
}

if (! function_exists('get_oneclick_user_session')) {
    function get_oneclick_user_session(): string
    {
        return session()->get('oneclick_user');
    }
}

if (! function_exists('get_success_transactions_count')) {
    function get_success_transactions_count(array $details): int
    {
        return count(
            array_filter(
                $details,
                fn ($detail) => $detail->status === 'AUTHORIZED'
            )
        );
    }
}

if (! function_exists('get_failed_transactions_count')) {
    function get_failed_transactions_count(array $details): int
    {
        return count(
            array_filter(
                $details,
                fn ($detail) => $detail->status !== 'AUTHORIZED'
            )
        );
    }
}

if (! function_exists('get_total_transactions_count')) {
    function get_total_transactions_count(array $details): int
    {
        return count($details);
    }
}

if (! function_exists('get_nullable_laravel_date')) {
    function get_nullable_laravel_date(?string $original_date = null): ?Carbon
    {
        return $original_date
            ? Carbon::parse($original_date)
            : null;
    }
}

if (! function_exists('get_success_transactions_amount')) {
    function get_success_transactions_amount(array $detail): int
    {
        return array_reduce(
            $detail,
            fn ($carry, $item) => $item->status === 'AUTHORIZED'
                ? $carry + $item->amount
                : $carry,
            0
        );
    }
}

if (! function_exists('get_failed_transactions_amount')) {
    function get_failed_transactions_amount(array $detail): int
    {
        return array_reduce(
            $detail,
            fn ($carry, $item) => $item->status !== 'AUTHORIZED'
                ? $carry + $item->amount
                : $carry,
            0
        );
    }
}

if (! function_exists('get_status_response')) {
    function get_status_response(array $detail): string
    {
        return get_success_transactions_count($detail) === get_total_transactions_count($detail)
            ? ResponseStatus::SUCCESS
            : ResponseStatus::FAILED;
    }
}

if (! function_exists('format_transaction_response')) {
    function format_transaction_response($response): array
    {
        return [
            'buy_order' => $response->buyOrder,
            'session_id' => $response->sessionId,
            'card_number' => $response->cardNumber,
            'expiration_date' => $response->expirationDate,
            'accounting_date' => $response->accountingDate,
            'transaction_date' => $response->transactionDate,
            'expiration_at' => get_nullable_laravel_date($response->expirationDate),
            'transaction_at' => get_nullable_laravel_date($response->transactionDate),
            'details' => $response->details,
            'success_transactions_count' => get_success_transactions_count($response->details),
            'failed_transactions_count' => get_failed_transactions_count($response->details),
            'total_transactions_count' => get_total_transactions_count($response->details),
            'success_transactions_amount' => get_success_transactions_amount($response->details),
            'failed_transactions_amount' => get_failed_transactions_amount($response->details),
            'status' => get_status_response($response->details),
        ];
    }
}

if (! function_exists('generate_oneclick_parent_id')) {
    function generate_oneclick_parent_id(string $id): string
    {
        $app_name = str()->of(config('app.name'))->replace([' ', '.'], ['_', '_'])->lower();
        $app_env = config('app.env');

        $full_name = $app_name.'-'.$app_env.'-'.$id;

        return str()->of($full_name)->take(10); // 17 with one character (1), 16 with 2 (10), 15 with 3 (100), 14 with 4 (1000), 13 with 5 (10000), 12 with 6 (100000), 11 with 7 (1000000), etc.
    }
}

if (! function_exists('get_transactable_fields')) {
    function get_transactable_fields(Model $model): array
    {
        return [
            'transactable_type' => $model::class,
            'transactable_id' => $model->id,
        ];
    }
}
