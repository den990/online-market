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
        Schema::create('product_photo_file', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('file_id');

            $table->index('product_id');
            $table->index('file_id');

            $table->foreign('product_id')->on('product')->references('id')->onDelete('cascade');
            $table->foreign('file_id')->on('file')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_photo_file', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['file_id']);
            $table->dropColumn('product_id');
            $table->dropColumn('file_id');
        });
        Schema::dropIfExists('product_photo_file');
    }
};
