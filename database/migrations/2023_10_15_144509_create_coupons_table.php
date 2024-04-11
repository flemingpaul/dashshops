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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->binary('image');
            $table->string('name');
            $table->float('price');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->integer('download_limit');
            $table->foreignId('retailer_id')->references('id')->on('retailers');
            $table->float('retail_price');
            $table->float('discount_now_price');
            $table->string('discount_percentage');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('qr_code')->nullable();
            $table->string('discount_description')->nullable();
            $table->string('discount_code')->nullable();
            $table->string('offer_type');
            $table->string('approval_status')->default('New');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('modified_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
