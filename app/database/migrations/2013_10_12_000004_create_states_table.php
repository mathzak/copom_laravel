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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedInteger('country_id');
            $table->char('country_code', 2);
            $table->string('fips_code', 255)->nullable();
            $table->string('iso2', 255)->nullable();
            $table->string('type', 191)->nullable();
            $table->decimal('latitude', total: 10, places: 8)->nullable();
            $table->decimal('longitude', total: 11, places: 8)->nullable();
            $table->timestamps();
            $table->boolean('flag')->default(true);
            $table->string('wikidataid', 255)->nullable();
            $table->geography('coordinates', subtype: 'point', srid: 4326)->nullable();
            $table->string('version', 10)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
