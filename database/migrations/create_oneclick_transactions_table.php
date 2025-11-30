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
            $table->unsignedBigInteger('success_transactions_count')->default(0);
            $table->unsignedBigInteger('failed_transactions_count')->default(0);
            $table->unsignedBigInteger('total_transactions_count')->default(0);
            $table->unsignedBigInteger('success_transactions_amount')->default(0);
            $table->unsignedBigInteger('failed_transactions_amount')->default(0);
            $table->string('status');
            $table->json('json');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
