<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner')->nullable();
            $table->string('name', 100);
            $table->text('description', 255)->nullable();
            $table->boolean('inalterable')->default(false);
            $table->boolean('superadmin')->default(false);
            $table->boolean('manager')->default(false);
            $table->boolean('active')->default(false);
            $table->jsonb('abilities')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->uuid('user_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');

        Schema::dropIfExists('roles');
    }
};
