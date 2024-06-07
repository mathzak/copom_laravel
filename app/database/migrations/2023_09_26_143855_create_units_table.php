<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner')->nullable();
            $table->string('name', 255);
            $table->text('shortpath')->nullable();
            $table->text('fullpath')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order')->default(1);
            $table->jsonb('children_id')->nullable();
            $table->string('nickname', 100);
            $table->date('founded')->nullable();
            $table->boolean('active')->default(true);
            $table->date('expires_at')->nullable();
            $table->string('cellphone')->nullable();
            $table->string('landline')->nullable();
            $table->string('email')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('country_code')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->string('state_code')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->string('postcode')->nullable();
            $table->string('address')->nullable();
            $table->string('complement')->nullable();
            $table->decimal('latitude', total: 10, places: 8)->nullable();
            $table->decimal('longitude', total: 11, places: 8)->nullable();
            $table->geography('coordinates', subtype: 'point', srid: 4326)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('unit_user', function (Blueprint $table) {
            $table->id();
            $table->uuid('owner')->nullable();
            $table->unsignedBigInteger('unit_id');
            $table->uuid('user_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->boolean('primary')->default(true);
            $table->boolean('temporary')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_user');

        Schema::dropIfExists('units');
    }
};
