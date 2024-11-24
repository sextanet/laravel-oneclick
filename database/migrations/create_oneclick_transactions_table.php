<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('oneclick_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('buy_order');
            $table->string('session_id');
            $table->string('card_number');
            $table->string('expiration_date');
            $table->string('accounting_date');
            $table->string('transaction_date');
            $table->json('details');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
