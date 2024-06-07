<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('user_contact_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner')->nullable();
            $table->string('name')->unique();
            $table->jsonb('translations')->nullable();
            $table->string('mask')->nullable();
            $table->string('validation')->nullable();
            $table->boolean('validate')->default(true);
            $table->boolean('required');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_contacts', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner');
            $table->foreign('owner')->references('id')->on('users')->onDelete('CASCADE');
            $table->unsignedInteger('type');
            $table->foreign('type')->references('id')->on('user_contact_types')->onDelete('CASCADE');
            $table->string('value')->unique();
            $table->boolean('primary');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_contacts');

        Schema::dropIfExists('user_contact_types');
    }
};
