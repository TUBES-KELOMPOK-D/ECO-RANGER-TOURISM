<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom organizer ke tabel events (jika belum ada)
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'organizer')) {
                $table->string('organizer')->nullable()->after('description');
            }
        });

        // Buat tabel event_messages untuk fitur chat grup event
        Schema::create('event_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_messages');

        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'organizer')) {
                $table->dropColumn('organizer');
            }
        });
    }
};
