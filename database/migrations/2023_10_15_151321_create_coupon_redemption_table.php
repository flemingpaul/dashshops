<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupon_redemption', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->references('id')->on('coupons');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('email');
            $table->string('redemption_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_redemption');
    }
};
