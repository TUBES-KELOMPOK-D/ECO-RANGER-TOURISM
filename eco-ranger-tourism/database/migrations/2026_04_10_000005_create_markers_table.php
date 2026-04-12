<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_layer_id')->nullable()->constrained('map_layers')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('shape_type')->default('Marker'); 
            $table->string('status')->nullable(); 
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('coordinates'); 
            $table->double('radius')->nullable(); 
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('markers');
    }
};
