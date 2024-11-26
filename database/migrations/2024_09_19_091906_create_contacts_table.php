<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id'); // Связь с анкетой
            $table->unsignedTinyInteger('type_id'); // Тип контакта (например, WhatsApp, Telegram)
            $table->string('value'); // Сам контакт (например, номер телефона)
            $table->timestamps();

            // Внешний ключ
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('contact_types')->onDelete('cascade');
        });

        Schema::create('contact_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if(app()->environment() !== 'production')
        {
            Schema::dropIfExists('contacts');
            Schema::dropIfExists('contact_types');
        }
    }
};
