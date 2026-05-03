<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon'); // e.g. 🪣, 🌱
            $table->text('description');
            $table->string('category'); // activity, level, first_time
            $table->integer('target');
            $table->string('target_column')->nullable(); // e.g. reports_count, points
            $table->string('target_condition'); // e.g. >=, =
            $table->integer('points_reward')->default(0);
            $table->string('level'); // bronze, silver, gold
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
