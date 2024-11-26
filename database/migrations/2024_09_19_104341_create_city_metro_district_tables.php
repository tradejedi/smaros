<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('metro_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade'); // связь с городом
            $table->timestamps();
        });

        Schema::create('metro_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('metro_station_id')->constrained('metro_stations')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade'); // связь с городом
            $table->timestamps();
        });

        Schema::create('district_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if(app()->environment() !== 'production')
        {
            Schema::dropIfExists('cities');
            Schema::dropIfExists('metro_stations');
            Schema::dropIfExists('metro_profile');
            Schema::dropIfExists('districts');
            Schema::dropIfExists('district_profile');

            Schema::table('profiles', function (Blueprint $table) {
                $table->dropForeign(['city_id']);
                $table->dropColumn('city_id');
            });
        }
    }
};
