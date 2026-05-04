<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            // A. Berdasarkan Aktivitas
            [
                'name' => 'Plastic Hunter',
                'slug' => 'plastic-hunter',
                'icon' => '🪣',
                'description' => 'Kirim 10 Laporan Isu Lingkungan',
                'category' => 'activity',
                'target' => 10,
                'target_column' => 'reports_count',
                'target_condition' => '>=',
                'points_reward' => 50,
                'level' => 'silver',
            ],
            [
                'name' => 'Tree Hugger',
                'slug' => 'tree-hugger',
                'icon' => '🌳',
                'description' => 'Ikut 5 Aksi Lingkungan',
                'category' => 'activity',
                'target' => 5,
                'target_column' => 'events_count',
                'target_condition' => '>=',
                'points_reward' => 50,
                'level' => 'silver',
            ],
            [
                'name' => 'Turtle Saver',
                'slug' => 'turtle-saver',
                'icon' => '🐢',
                'description' => 'Dapatkan 3 Laporan Diverifikasi',
                'category' => 'activity',
                'target' => 3,
                'target_column' => 'verified_reports_count',
                'target_condition' => '>=',
                'points_reward' => 30,
                'level' => 'bronze',
            ],
            [
                'name' => 'Academy Master',
                'slug' => 'academy-master',
                'icon' => '🎓',
                'description' => 'Selesaikan 5 Modul Akademi',
                'category' => 'activity',
                'target' => 5,
                'target_column' => 'academy_modules_count',
                'target_condition' => '>=',
                'points_reward' => 50,
                'level' => 'silver',
            ],

            // B. Berdasarkan Level (Poin)
            [
                'name' => 'Eco-Newbie',
                'slug' => 'eco-newbie',
                'icon' => '🌱',
                'description' => 'Kumpulkan 100 poin',
                'category' => 'level',
                'target' => 100,
                'target_column' => 'points',
                'target_condition' => '>=',
                'points_reward' => 0,
                'level' => 'bronze',
            ],
            [
                'name' => 'Eco-Warrior',
                'slug' => 'eco-warrior',
                'icon' => '⚔️',
                'description' => 'Kumpulkan 500 poin',
                'category' => 'level',
                'target' => 500,
                'target_column' => 'points',
                'target_condition' => '>=',
                'points_reward' => 0,
                'level' => 'silver',
            ],
            [
                'name' => 'Eco-Ranger',
                'slug' => 'eco-ranger',
                'icon' => '🦸',
                'description' => 'Kumpulkan 1000 poin',
                'category' => 'level',
                'target' => 1000,
                'target_column' => 'points',
                'target_condition' => '>=',
                'points_reward' => 0,
                'level' => 'gold',
            ],

            // C. First Time Badge
            [
                'name' => 'First Report',
                'slug' => 'first-report',
                'icon' => '📝',
                'description' => 'Kirim Laporan Pertama',
                'category' => 'first_time',
                'target' => 1,
                'target_column' => 'reports_count',
                'target_condition' => '>=',
                'points_reward' => 10,
                'level' => 'bronze',
            ],
            [
                'name' => 'First Event',
                'slug' => 'first-event',
                'icon' => '🎉',
                'description' => 'Ikut Aksi Lingkungan Pertama',
                'category' => 'first_time',
                'target' => 1,
                'target_column' => 'events_count',
                'target_condition' => '>=',
                'points_reward' => 10,
                'level' => 'bronze',
            ],
            [
                'name' => 'First Verified',
                'slug' => 'first-verified',
                'icon' => '⭐',
                'description' => 'Laporan Pertama Diverifikasi',
                'category' => 'first_time',
                'target' => 1,
                'target_column' => 'verified_reports_count',
                'target_condition' => '>=',
                'points_reward' => 10,
                'level' => 'bronze',
            ],
            [
                'name' => 'First Module',
                'slug' => 'first-module',
                'icon' => '📖',
                'description' => 'Selesaikan Modul Pertama',
                'category' => 'first_time',
                'target' => 1,
                'target_column' => 'academy_modules_count',
                'target_condition' => '>=',
                'points_reward' => 10,
                'level' => 'bronze',
            ],
        ];

        foreach ($badges as $badge) {
            \App\Models\Badge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }
    }
}
