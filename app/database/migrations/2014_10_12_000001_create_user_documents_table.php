<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('user_document_types', function (Blueprint $table) {
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

        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner');
            $table->foreign('owner')->references('id')->on('users')->onDelete('CASCADE');
            $table->unsignedInteger('type');
            $table->foreign('type')->references('id')->on('user_document_types')->onDelete('CASCADE');
            $table->string('value')->unique();
            $table->string('category')->nullable();
            $table->date('issued_at')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('country_code')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->string('state_code')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->date('expires_at')->nullable();
            $table->jsonb('complementary_data')->nullable();
            $table->boolean('primary');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_documents');

        Schema::dropIfExists('user_document_types');
    }
};
