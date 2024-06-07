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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->unsignedInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('CASCADE');
            $table->string('state_code', 255);
            $table->unsignedInteger('country_id');
            $table->char('country_code', 2);
            $table->decimal('latitude', total: 10, places: 8);
            $table->decimal('longitude', total: 11, places: 8);
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
        Schema::dropIfExists('cities');
    }
};
