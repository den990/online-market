<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\City;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $cities = array_map(
            function ($name) {
                return ['name' => $name];
            },
            array_keys(City::codeToValue)
        );

        Schema::create('city', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name city');
        });

        DB::table('city')->insert($cities);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city');
    }
};
