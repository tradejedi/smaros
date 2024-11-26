<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('seo_models', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');       // "App\Models\Post"
            $table->unsignedBigInteger('model_id'); // ID записи
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });

    }

    public function down()
    {
        Schema::dropIfExists('seo_models');
    }
};
