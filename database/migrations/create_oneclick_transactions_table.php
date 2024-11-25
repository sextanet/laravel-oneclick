<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SextaNet\LaravelOneclick\Models\OneclickCard;

return new class extends Migration
{
    public function up()
    {
        Schema::create('oneclick_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OneclickCard::class);
            $table->morphs('transactable');
            $table->string('buy_order');
            $table->string('session_id')->nullable();
            $table->string('card_number');
            $table->string('expiration_date')->nullable();
            $table->string('accounting_date')->nullable();
            $table->string('transaction_date')->nullable();
            $table->datetime('expiration_at')->nullable();
            $table->datetime('transaction_at')->nullable();
            $table->unsignedBigInteger('success_transactions_count')->default(0);
            $table->unsignedBigInteger('failed_transactions_count')->default(0);
            $table->unsignedBigInteger('total_transactions_count')->default(0);
            $table->unsignedBigInteger('success_transactions_amount')->default(0);
            $table->unsignedBigInteger('failed_transactions_amount')->default(0);
            $table->string('status');
            $table->json('details');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
