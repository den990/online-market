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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('categories_id');

            $table->index('product_id');
            $table->index('categories_id');

            $table->foreign('product_id')->on('product')->references('id')->onDelete('cascade');
            $table->foreign('categories_id')->on('categories')->references('id')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['categories_id']);
            $table->dropColumn('product_id');
            $table->dropColumn('categories_id');
        });
        Schema::dropIfExists('product_categories');
    }
};
