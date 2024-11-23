<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('oneclick_cards', function (Blueprint $table) {
            $table->id();
            $table->morphs('oneclickable');
            $table->string('username')->unique();
            $table->string('tbk_user')->unique();
            $table->string('authorization_code');
            $table->string('card_type');
            $table->string('card_number');
            $table->string('tbk_secret_token')->nullable();
            $table->string('tbk_commerce_code')->nullable();
            $table->string('name')->nullable();
            $table->boolean('is_favourite')->default(false);
            $table->datetime('last_time_used_successfully')->nullable();
            $table->unsignedInteger('success_count')->nullable();
            $table->datetime('last_time_used_with_errors')->nullable();
            $table->unsignedInteger('error_count')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
