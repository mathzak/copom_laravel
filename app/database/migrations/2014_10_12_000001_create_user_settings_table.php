<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('user_setting_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner')->nullable();
            $table->string('name')->unique();
            $table->string('type');
            $table->string('label');
            $table->text('description')->nullable();
            $table->jsonb('translations')->nullable();
            $table->string('mask')->nullable();
            $table->boolean('unmask')->default(true);
            $table->string('validation')->nullable();
            $table->boolean('validate')->default(true);
            $table->boolean('required')->default(true);
            $table->boolean('editable')->default(true);
            $table->boolean('visible')->default(true);
            $table->boolean('autoClear')->default(true);
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->string('format')->nullable();
            $table->integer('length')->nullable();
            $table->integer('step')->nullable();
            $table->boolean('rounding')->default(true);
            $table->boolean('multiple')->default(false);
            $table->string('range')->nullable();
            $table->string('min')->nullable();
            $table->string('max')->nullable();
            $table->integer('minCount')->nullable();
            $table->integer('maxCount')->nullable();
            $table->integer('minFraction')->nullable();
            $table->integer('maxFraction')->nullable();
            $table->jsonb('values')->nullable();
            $table->string('default_value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner');
            $table->foreign('owner')->references('id')->on('users')->onDelete('CASCADE');
            $table->unsignedInteger('type');
            $table->foreign('type')->references('id')->on('user_setting_types')->onDelete('CASCADE');
            $table->string('value')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');

        Schema::dropIfExists('user_setting_types');
    }
};
