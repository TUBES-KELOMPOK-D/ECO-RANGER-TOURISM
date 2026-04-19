<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('type', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('map_layers', function (Blueprint $table) {
            $table->id();
            $table->string('layer_name')->nullable();
            $table->string('layer_type', 100)->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_layers');
        Schema::dropIfExists('layers');
    }
};
