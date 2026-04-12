<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DestinasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinasi = [
            ['name' => 'Telkom University Bandung', 'description' => 'Kampus Hijau. Pusat Pendidikan Teknologi dan Lingkungan.', 'location' => 'Bandung', 'latitude' => -6.97298335888248, 'longitude' => 107.63044873089636, 'status' => 'green', ]
        ];

        foreach ($destinasi as $data) {
            \App\Models\Destinasi::create($data);
        }       
    }
}
