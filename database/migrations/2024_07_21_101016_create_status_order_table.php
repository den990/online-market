<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('status_order', function (Blueprint $table) {
            $table->id();
            $table->string('status');
        });

        DB::table('status_order')->insert([
            ['status' => 'Processing'],
            ['status' => 'Complete'],
            ['status' => 'Canceled'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_order');
    }
};
