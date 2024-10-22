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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'accommodation')) {
                $table->dropColumn('accommodation');
            }
            $table->unsignedBigInteger('city_id')->default(1)->after('phone');

            $table->index('city_id');

            $table->foreign('city_id')->on('city')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
            $table->string('accommodation')->after('phone')->nullable();
        });
    }
};
