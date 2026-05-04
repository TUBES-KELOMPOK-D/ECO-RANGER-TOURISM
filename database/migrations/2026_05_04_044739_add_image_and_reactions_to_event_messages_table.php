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
        Schema::table('event_messages', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('message');
        });

        Schema::create('event_message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_message_id')->constrained('event_messages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('reaction');
            $table->timestamps();
            
            // User can only react once per type per message, actually let's just make it unique per user per message
            $table->unique(['event_message_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_message_reactions');
        
        Schema::table('event_messages', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
