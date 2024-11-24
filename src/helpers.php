<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
    function get_success_transactions_count($response): int
    {
        return count(
            array_filter(
                $response->details,
                fn ($detail) => $detail->status === 'AUTHORIZED'
            )
        );
    }
}

if (! function_exists('get_failed_transactions_count')) {
    function get_failed_transactions_count($response): int
    {
        return count(
            array_filter(
                $response->details,
                fn ($detail) => $detail->status !== 'AUTHORIZED'
            )
        );
    }
}

if (! function_exists('get_total_transactions_count')) {
    function get_total_transactions_count($response): int
    {
        return count($response->details);
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
            'success_transactions_count' => get_success_transactions_count($response),
            'failed_transactions_count' => get_failed_transactions_count($response),
            'total_transactions_count' => get_total_transactions_count($response),
        ];
    }
}
