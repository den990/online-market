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
        Schema::create('product', function (Blueprint $table) {
            $table->id()->comment('Primary key');
            $table->string('name')->comment('Name product');
            $table->text('description')->comment('Description');
            $table->unsignedInteger('evaluation')->default(0)->comment('Evaluation');
            $table->unsignedBigInteger('img_id')->nullable()->comment('Image id');
            $table->unsignedBigInteger('author_id')->nullable()->comment('User id');
            $table->timestamps();
            $table->softDeletes();

            $table->index('img_id');
            $table->index('author_id');

            $table->foreign('img_id')->on('file')->references('id');
            $table->foreign('author_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropForeign(['img_id']);
            $table->dropForeign(['author_id']);
            $table->dropIndex(['img_id']);
            $table->dropIndex(['author_id']);
        });

        Schema::dropIfExists('product');
    }
};
