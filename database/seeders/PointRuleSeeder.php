<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            [
                'action_key' => 'report_issue',
                'action_name' => 'Lapor Isu Lingkungan',
                'points_reward' => 10,
                'icon' => '📋',
                'description' => 'Buat laporan tentang masalah lingkungan',
            ],
            [
                'action_key' => 'community_action',
                'action_name' => 'Ikuti event Lingkungan',
                'points_reward' => 50,
                'icon' => '👥',
                'description' => 'Berpartisipasi dalam kegiatan komunitas',
            ],
            [
                'action_key' => 'verify_report',
                'action_name' => 'Laporan Diverifikasi',
                'points_reward' => 5,
                'icon' => '✅',
                'description' => 'Laporan Anda telah divalidasi oleh tim',
            ],
            [
                'action_key' => 'forum_discussion',
                'action_name' => 'Diskusi Forum',
                'points_reward' => 15,
                'icon' => '💬',
                'description' => 'Berbagi wawasan di forum komunitas',
            ],
            [
                'action_key' => 'share_content',
                'action_name' => 'Bagikan Konten',
                'points_reward' => 20,
                'icon' => '📸',
                'description' => 'Bagikan foto/video mengenai inisiatif hijau',
            ],
            [
                'action_key' => 'quiz',
                'action_name' => 'Menyelesaikan Modul Akademi',
                'points_reward' => 20,
                'icon' => '🎓',
                'description' => 'Menyelesaikan kuis di Green Academy',
            ],
        ];

        foreach ($rules as $rule) {
            \App\Models\PointRule::updateOrCreate(
                ['action_key' => $rule['action_key']],
                $rule
            );
        }
    }
}
