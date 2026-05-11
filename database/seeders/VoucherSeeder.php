<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Voucher::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Voucher Wisata Rp 500.000',
                'description' => 'Voucher eksklusif senilai Rp 500.000 untuk petualangan seru Anda di destinasi pilihan Eco Ranger.',
                'poin_required' => 1500,
                'kategori' => 'lainnya',
            ]
        );

        Voucher::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Voucher Wisata Rp 250.000',
                'description' => 'Nikmati diskon perjalanan senilai Rp 250.000 sebagai penghargaan atas kontribusi luar biasa Anda.',
                'poin_required' => 1000,
                'kategori' => 'lainnya',
            ]
        );

        Voucher::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Voucher Wisata Rp 100.000',
                'description' => 'Voucher apresiasi senilai Rp 100.000 untuk mendukung aksi pelestarian lingkungan Anda.',
                'poin_required' => 500,
                'kategori' => 'lainnya',
            ]
        );
    }
}
