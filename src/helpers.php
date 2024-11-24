<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

function put_oneclickable_session(Model $model): void
{
    session()->put('oneclickable', $model);
}

function get_oneclickable_session(): Model
{
    return session()->get('oneclickable');
}

function put_oneclick_user_session(string $username): void
{
    session()->put('oneclick_user', $username);
}

function get_oneclick_user_session(): string
{
    return session()->get('oneclick_user');
}

function get_success_transactions_count($response): int
{
    $success_count = 0;

    foreach ($response->details as $detail) {
        if ($detail->status === 'AUTHORIZED') {
            $success_count++;
        }
    }

    return $success_count;
}

function get_failed_transactions_count($response): int
{
    $failed_count = 0;

    foreach ($response->details as $detail) {
        if ($detail->status !== 'AUTHORIZED') {
            $failed_count++;
        }
    }

    return $failed_count;
}

function get_total_transactions_count($response): int
{
    return count($response->details);
}

function get_nullable_laravel_date(?string $original_date = null): ?Carbon
{
    return $original_date
        ? Carbon::parse($original_date)
        : null;
}

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
