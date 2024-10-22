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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedInteger('total_price');
            $table->timestamps();

            $table->index('user_id');
            $table->index('status_id');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->foreign('status_id')->on('status_order')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['status_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('status_id');
        });
        Schema::dropIfExists('order');
    }
};
