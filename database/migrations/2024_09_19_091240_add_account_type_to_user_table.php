<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       /* Schema::table('users', function (Blueprint $table) {
            $table->string('account_type')->default('regular'); // Поле для типа аккаунта
        });*/
    }

    public function down(): void
    {
       /* if(app()->environment() !== 'production') {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('account_type');
            });
        }*/
    }
};
