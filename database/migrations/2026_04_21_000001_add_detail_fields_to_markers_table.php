<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('markers', function (Blueprint $table) {
            $table->string('location_name')->nullable()->after('title');
            $table->decimal('eco_health_score', 3, 1)->nullable()->after('image_path');
            $table->integer('total_reports')->default(0)->after('eco_health_score');
            $table->string('kebersihan')->nullable()->after('total_reports');
            $table->string('akses')->nullable()->after('kebersihan');
            $table->string('populasi')->nullable()->after('akses');
            $table->json('eco_rules')->nullable()->after('populasi');
            $table->string('category')->nullable()->after('eco_rules');
        });
    }

    public function down(): void
    {
        Schema::table('markers', function (Blueprint $table) {
            $table->dropColumn([
                'location_name',
                'eco_health_score',
                'total_reports',
                'kebersihan',
                'akses',
                'populasi',
                'eco_rules',
                'category',
            ]);
        });
    }
};
