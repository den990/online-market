<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $userRoleId = DB::table('user_role')->where('name', 'User')->value('id');

        Schema::table('users', function (Blueprint $table) use ($userRoleId) {
            $table->unsignedBigInteger('role_id')->default($userRoleId);

            $table->foreign('role_id')->on('user_role')->references('id')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
