<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Report;
use App\Models\Action;
use Illuminate\Support\Facades\Hash;

class GreenTourSeeder extends Seeder
{
    public function run(): void
    {
        $andi = User::create([
            'name' => 'Andi Saputra',
            'email' => 'andi@greentour.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'eco_points' => 1240,
            'eco_level' => 'Eco-Ranger',
        ]);


        Report::create([
            'user_id' => $andi->id,
            'title' => 'Sampah Plastik di Pantai',
            'description' => 'Banyak sampah plastik berserakan di area pantai Kuta.',
            'latitude' => 8.718,
            'longitude' => 115.169,
            'status' => 'menunggu',
            'report_date' => '2026-04-01',
        ]);

        Report::create([
            'user_id' => $andi->id,
            'title' => 'Terumbu Karang Rusak',
            'description' => 'Ada area terumbu karang yang rusak akibat jangkar kapal.',
            'latitude' => 1.500,
            'longitude' => 124.500,
            'status' => 'diverifikasi',
            'report_date' => '2026-04-05',
        ]);

    }
}
