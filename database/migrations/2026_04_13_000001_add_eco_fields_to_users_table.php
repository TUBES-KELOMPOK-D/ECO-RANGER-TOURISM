<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('eco_points')->default(0)->after('role');
            $table->string('eco_level')->default('Eco-Newbie')->after('eco_points');
            $table->string('photo')->nullable()->after('eco_level');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['eco_points', 'eco_level', 'photo']);
        });
    }
};