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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner')->nullable();
            $table->string('name', 100);
            $table->boolean('active')->default(true);
            $table->unsignedInteger('country_id')->nullable();
            $table->string('country_code')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->string('state_code')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->string('authority', 100);
            $table->boolean('day_off')->default(true);
            $table->integer('day')->nullable();
            $table->integer('month')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('easter')->default(false);
            $table->boolean('sum_difference')->nullable();
            $table->string('difference_start', 100)->nullable();
            $table->string('difference_end', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
