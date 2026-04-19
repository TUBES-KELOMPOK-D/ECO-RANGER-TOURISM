<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('image_path');
            $table->string('duration')->nullable()->after('points');
        });

        Schema::table('kuis', function (Blueprint $table) {
            $table->foreignId('artikel_id')
                ->nullable()
                ->after('id')
                ->constrained('artikels')
                ->cascadeOnDelete();
            $table->json('questions')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('kuis', function (Blueprint $table) {
            $table->dropForeign(['artikel_id']);
            $table->dropColumn(['artikel_id', 'questions']);
        });

        Schema::table('artikels', function (Blueprint $table) {
            $table->dropColumn(['points', 'duration']);
        });
    }
};
