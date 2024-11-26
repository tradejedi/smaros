<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('seo_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();    // 'title', 'description', ...
            $table->text('global_template')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('seo_keys');
    }
};
