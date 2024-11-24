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

function format_transaction_response($response): array
{
    $success_count = 0;
    $failed_count = 0;
    $total_count = count($response->details);

    foreach ($response->details as $detail) {
        if ($detail->status === 'AUTHORIZED') {
            $success_count++;
        } else {
            $failed_count++;
        }
    }

    return [
        'buy_order' => $response->buyOrder,
        'session_id' => $response->sessionId,
        'card_number' => $response->cardNumber,
        'expiration_date' => $response->expirationDate,
        'accounting_date' => $response->accountingDate,
        'transaction_date' => $response->transactionDate,
        'expiration_at' => $response->expirationDate ? Carbon::parse($response->expirationDate) : null,
        'transaction_at' => $response->transactionDate ? Carbon::parse($response->transactionDate) : null,
        'details' => $response->details,
        'success_transactions_count' => $success_count,
        'failed_transactions_count' => $failed_count,
        'total_transactions_count' => $total_count,
    ];
}
