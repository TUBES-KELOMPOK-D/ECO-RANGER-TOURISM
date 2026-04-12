<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed akun admin default dan satu akun user demo.
     *
     * Jalankan dengan: php artisan db:seed --class=AdminUserSeeder
     *
     * PENTING: Ganti password admin di environment production!
     */
    public function run(): void
    {
        // -------------------------------------------------------
        // Akun Admin Default
        // -------------------------------------------------------
        User::updateOrCreate(
            ['email' => 'admin@eco-ranger.id'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('Admin@123!'),
                'role'     => 'admin',
            ]
        );

        $this->command->info('✅ Admin default berhasil dibuat: admin@eco-ranger.id / Admin@123!');

        // -------------------------------------------------------
        // Akun User Demo
        // -------------------------------------------------------
        User::updateOrCreate(
            ['email' => 'user@eco-ranger.id'],
            [
                'name'     => 'Eco Ranger User',
                'password' => Hash::make('User@123!'),
                'role'     => 'user',
            ]
        );

        $this->command->info('✅ User demo berhasil dibuat: user@eco-ranger.id / User@123!');
    }
}
