<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marker;

class MarkerDetailSeeder extends Seeder
{
    public function run(): void
    {
        $markers = [
            [
                'shape_type' => 'Marker',
                'status' => 'green',
                'title' => 'Hutan Mangrove Muara Gembong',
                'location_name' => 'Muara Gembong, Jawa Barat',
                'description' => 'Lokasi Penyeimbangan Karbon. Area penanaman mangrove untuk menyerap emisi karbon. Anda bisa berdonasi atau ikut menanam langsung di lokasi. Hutan mangrove ini menjadi habitat alami bagi berbagai spesies burung dan ikan, serta berfungsi sebagai pelindung garis pantai dari abrasi.',
                'coordinates' => [-6.01, 107.03],
                'latitude' => -6.01,
                'longitude' => 107.03,
                'eco_health_score' => 8.5,
                'total_reports' => 1200,
                'kebersihan' => '4/5',
                'akses' => 'Mudah',
                'populasi' => 'Sedang',
                'category' => 'Sangat Terjaga',
                'eco_rules' => [
                    ['text' => 'Dilarang membawa plastik sekali pakai.', 'type' => 'allowed'],
                    ['text' => 'Gunakan jalur trekking yang sudah disediakan.', 'type' => 'allowed'],
                    ['text' => 'Dilarang memberi makan satwa liar!', 'type' => 'warning'],
                ],
                'user_id' => 1,
            ],
            [
                'shape_type' => 'Marker',
                'status' => 'green',
                'title' => 'Taman Nasional Ujung Kulon',
                'location_name' => 'Pandeglang, Banten',
                'description' => 'Taman Nasional Ujung Kulon merupakan habitat alami badak jawa yang terancam punah. Kawasan ini menyimpan keanekaragaman hayati yang luar biasa, mulai dari hutan hujan tropis dataran rendah hingga ekosistem pantai dan terumbu karang. Area konservasi ini sangat dijaga untuk memastikan kelestarian flora dan fauna endemik.',
                'coordinates' => [-6.75, 105.33],
                'latitude' => -6.75,
                'longitude' => 105.33,
                'eco_health_score' => 9.2,
                'total_reports' => 3400,
                'kebersihan' => '5/5',
                'akses' => 'Sulit',
                'populasi' => 'Rendah',
                'category' => 'Sangat Terjaga',
                'eco_rules' => [
                    ['text' => 'Wajib didampingi pemandu resmi.', 'type' => 'allowed'],
                    ['text' => 'Dilarang membuang sampah di area konservasi.', 'type' => 'allowed'],
                    ['text' => 'Dilarang mengambil flora atau fauna dari taman nasional!', 'type' => 'warning'],
                    ['text' => 'Jaga jarak aman dari satwa liar!', 'type' => 'warning'],
                ],
                'user_id' => 1,
            ],
            [
                'shape_type' => 'Marker',
                'status' => 'yellow',
                'title' => 'Pantai Kuta Bali',
                'location_name' => 'Kuta, Bali',
                'description' => 'Pantai Kuta merupakan salah satu destinasi wisata paling terkenal di Bali. Saat ini kondisi pantai membutuhkan perhatian lebih terhadap kebersihan dan pengelolaan sampah. Program pembersihan rutin sedang dilakukan oleh komunitas lokal dan relawan untuk menjaga kelestarian pantai.',
                'coordinates' => [-8.718, 115.169],
                'latitude' => -8.718,
                'longitude' => 115.169,
                'eco_health_score' => 5.8,
                'total_reports' => 850,
                'kebersihan' => '2/5',
                'akses' => 'Mudah',
                'populasi' => 'Tinggi',
                'category' => 'Perlu Perhatian',
                'eco_rules' => [
                    ['text' => 'Buang sampah pada tempatnya.', 'type' => 'allowed'],
                    ['text' => 'Gunakan sunscreen ramah lingkungan.', 'type' => 'allowed'],
                    ['text' => 'Hindari penggunaan plastik sekali pakai!', 'type' => 'warning'],
                ],
                'user_id' => 1,
            ],
        ];

        foreach ($markers as $data) {
            Marker::create($data);
        }
    }
}
