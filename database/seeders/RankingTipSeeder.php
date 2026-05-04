<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RankingTip;

class RankingTipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tips = [
            [
                'title' => 'Lapor Isu Lingkungan',
                'description' => 'Laporkan isu lingkungan di sekitar Anda untuk mendapatkan poin awal.',
                'icon' => 'camera',
            ],
            [
                'title' => 'Ikuti Event',
                'description' => 'Ikuti event-event aksi lingkungan yang diselenggarakan untuk poin besar.',
                'icon' => 'calendar',
            ],
            [
                'title' => 'Verifikasi Laporan',
                'description' => 'Pastikan laporan Anda akurat agar cepat diverifikasi oleh Admin.',
                'icon' => 'check-circle',
            ],
            [
                'title' => 'Green Academy',
                'description' => 'Selesaikan modul pembelajaran di Green Academy untuk menambah wawasan dan poin.',
                'icon' => 'book-open',
            ],
        ];

        foreach ($tips as $tip) {
            RankingTip::updateOrCreate(['title' => $tip['title']], $tip);
        }
    }
}
