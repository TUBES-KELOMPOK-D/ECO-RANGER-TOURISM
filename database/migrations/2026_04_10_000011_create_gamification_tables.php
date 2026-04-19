<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('poin_required')->default(0);
            $table->enum('kategori', ['diskon', 'produk', 'layanan', 'lainnya'])->default('lainnya');
            $table->timestamps();
        });

        Schema::create('poin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->enum('source', ['report', 'event', 'activity'])->nullable();
            $table->timestamps();
        });

        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->enum('status', ['menunggu', 'digunakan', 'kadaluarsa'])->default('menunggu');
            $table->timestamp('redeemed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_vouchers');
        Schema::dropIfExists('poin');
        Schema::dropIfExists('vouchers');
    }
};
