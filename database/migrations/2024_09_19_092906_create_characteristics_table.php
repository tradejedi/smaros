<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->enum('type', ['list', 'range', 'boolean']);
            $table->enum('group', ['variants', 'sex', 'massage', 'sm', 'striptease', 'extreme', 'other', 'price']);
            $table->decimal('min_value', 10, 2)->nullable();
            $table->decimal('max_value', 10, 2)->nullable();
            $table->timestamps();

            // Если нужен внешний ключ на profiles:
            // $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
        });

        Schema::create('attribute_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attribute_id');
            $table->string('value', 255);
            $table->timestamps();

            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });

        Schema::create('profile_attribute_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('profile_id');           // Ссылка на товар/объект
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('attribute_value_id')->nullable(); // Для типа list
            $table->decimal('value_decimal', 10, 2)->nullable(); // Для типа range
            $table->boolean('value_boolean')->nullable();       // Для типа boolean
            $table->timestamps();

            // Если есть таблица products:
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('profile_attribute_values');
    }
};
