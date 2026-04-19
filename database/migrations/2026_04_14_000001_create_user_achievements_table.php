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
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('achievement_key')->comment('Unique key untuk achievement: plastic_hunter, green_warrior, earth_guardian, eco_hero');
            $table->string('achievement_name');
            $table->text('description')->nullable();
            $table->string('icon')->default('🎁')->comment('Emoji atau URL icon');
            $table->integer('target')->comment('Target jumlah untuk menyelesaikan achievement');
            $table->integer('current')->default(0)->comment('Progress saat ini');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'achievement_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
