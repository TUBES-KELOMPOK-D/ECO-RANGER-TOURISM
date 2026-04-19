<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Report;
use App\Models\ForumDiskusi;
use App\Models\SharedContent;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EcoRankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        $user1 = $users->first();
        $user2 = $users->count() > 1 ? $users->get(1) : null;
        $user3 = $users->count() > 2 ? $users->get(2) : null;

        // Seed Reports for User 1 (Eco-Ranger)
        if ($user1) {
            for ($i = 1; $i <= 10; $i++) {
                Report::create([
                    'user_id' => $user1->id,
                    'title' => "Laporan Sampah Plastik #$i",
                    'description' => 'Ditemukan tumpukan sampah plastik di area publik.',
                    'latitude' => -6.1234 + (rand(-100, 100) / 10000),
                    'longitude' => 106.5678 + (rand(-100, 100) / 10000),
                    'status' => $i <= 7 ? 'diverifikasi' : 'menunggu',
                    'report_date' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Forum Posts
            for ($i = 1; $i <= 20; $i++) {
                ForumDiskusi::create([
                    'user_id' => $user1->id,
                    'topic' => "Diskusi Eco #$i",
                    'message' => "Ini adalah diskusi penting tentang lingkungan. Kita harus bergerak cepat dan mengambil tindakan nyata untuk masa depan yang lebih hijau.",
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Shared Contents
            for ($i = 1; $i <= 10; $i++) {
                SharedContent::create([
                    'user_id' => $user1->id,
                    'title' => "Inisiatif Hijau #$i",
                    'description' => "Program komunitas untuk mengurangi jejak karbon dan menjaga kelestarian lingkungan.",
                    'media_path' => "/images/eco-content-$i.jpg",
                    'type' => 'image',
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            $this->command->info("✓ User 1 points: " . $user1->total_points);
        }

        // Seed Reports for User 2 (Eco-Warrior)
        if ($user2) {
            for ($i = 1; $i <= 5; $i++) {
                Report::create([
                    'user_id' => $user2->id,
                    'title' => "Laporan Lingkungan #$i",
                    'description' => 'Masalah lingkungan yang perlu diperhatikan.',
                    'latitude' => -6.2000 + (rand(-100, 100) / 10000),
                    'longitude' => 106.6000 + (rand(-100, 100) / 10000),
                    'status' => $i <= 3 ? 'diverifikasi' : 'menunggu',
                    'report_date' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Forum Posts
            for ($i = 1; $i <= 15; $i++) {
                ForumDiskusi::create([
                    'user_id' => $user2->id,
                    'topic' => "Topik Forum #$i",
                    'message' => "Berbagi pengalaman dan pengetahuan tentang konservasi lingkungan.",
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Shared Contents
            for ($i = 1; $i <= 8; $i++) {
                SharedContent::create([
                    'user_id' => $user2->id,
                    'title' => "Konten Hijau #$i",
                    'description' => "Dokumentasi kegiatan ramah lingkungan.",
                    'media_path' => "/images/content-$i.jpg",
                    'type' => 'image',
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            $this->command->info("✓ User 2 points: " . $user2->total_points);
        }

        // Seed Reports for User 3 (Eco-Leader/Member)
        if ($user3) {
            for ($i = 1; $i <= 3; $i++) {
                Report::create([
                    'user_id' => $user3->id,
                    'title' => "Laporan #$i",
                    'description' => 'Laporan singkat tentang isu lingkungan.',
                    'latitude' => -6.3000 + (rand(-100, 100) / 10000),
                    'longitude' => 106.7000 + (rand(-100, 100) / 10000),
                    'status' => $i == 1 ? 'diverifikasi' : 'menunggu',
                    'report_date' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Forum Posts
            for ($i = 1; $i <= 10; $i++) {
                ForumDiskusi::create([
                    'user_id' => $user3->id,
                    'topic' => "Post Forum #$i",
                    'message' => "Berkontribusi dalam diskusi komunitas eco.",
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Shared Contents
            for ($i = 1; $i <= 5; $i++) {
                SharedContent::create([
                    'user_id' => $user3->id,
                    'title' => "Share #$i",
                    'description' => "Berbagi inisiatif lingkungan.",
                    'media_path' => "/images/share-$i.jpg",
                    'type' => 'image',
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            $this->command->info("✓ User 3 points: " . $user3->total_points);
        }

        // Create/Attach to events
        $events = Event::all();
        if ($events->isNotEmpty()) {
            if ($user1) {
                $user1->eventParticipations()->attach($events->random(min(5, $events->count()))->pluck('id'));
            }
            if ($user2) {
                $user2->eventParticipations()->attach($events->random(min(3, $events->count()))->pluck('id'));
            }
            if ($user3) {
                $user3->eventParticipations()->attach($events->random(min(2, $events->count()))->pluck('id'));
            }
        }

        $this->command->info('✅ Eco Rankings seeder completed!');
    }
}
