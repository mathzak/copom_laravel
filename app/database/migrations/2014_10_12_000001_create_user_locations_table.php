<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('user_location_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner')->nullable();
            $table->string('name')->unique();
            $table->jsonb('translations')->nullable();
            $table->boolean('required');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_locations', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner')->nullable();
            $table->foreign('owner')->references('id')->on('users')->onDelete('CASCADE');
            $table->unsignedInteger('type');
            $table->foreign('type')->references('id')->on('user_location_types')->onDelete('CASCADE');
            $table->string('address');
            $table->string('complement')->nullable();
            $table->string('reference')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('country_code')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->string('state_code')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->string('postcode')->nullable();
            $table->decimal('latitude', total: 10, places: 8)->nullable();
            $table->decimal('longitude', total: 11, places: 8)->nullable();
            $table->geography('coordinates', subtype: 'point', srid: 4326)->nullable();
            $table->boolean('primary');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_locations');

        Schema::dropIfExists('user_location_types');
    }
};
