<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('seo_model_templates', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');      // например "App\Models\Post"
            $table->unsignedBigInteger('seo_key_id');
            $table->text('template')->nullable();
            $table->timestamps();

            $table->foreign('seo_key_id')
                ->references('id')
                ->on('seo_keys')
                ->onDelete('cascade');

            // При желании можно добавить уникальный индекс (model_type, seo_key_id)
        });

    }

    public function down()
    {
        Schema::dropIfExists('seo_keys');
    }
};
