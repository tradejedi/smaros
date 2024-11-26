<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('seo_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seo_model_id');
            $table->unsignedBigInteger('seo_key_id');
            $table->text('value')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('seo_model_id')
                ->references('id')
                ->on('seo_models')
                ->onDelete('cascade');

            $table->foreign('seo_key_id')
                ->references('id')
                ->on('seo_keys')
                ->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('seo_values');
    }
};
