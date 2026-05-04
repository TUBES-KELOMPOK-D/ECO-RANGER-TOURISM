<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Make destinasi_id nullable (reviews can now belong to markers too)
            $table->foreignId('destinasi_id')->nullable()->change();

            // Add marker_id for linking reviews to markers
            $table->foreignId('marker_id')->nullable()->after('destinasi_id')->constrained('markers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['marker_id']);
            $table->dropColumn('marker_id');
        });
    }
};
