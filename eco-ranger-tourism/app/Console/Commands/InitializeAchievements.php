<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\RankingService;

class InitializeAchievements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'achievements:initialize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize achievements for all existing users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            RankingService::initializeUserAchievements($user);
            $count++;
        }

        $this->info("✓ Achievements initialized for {$count} users");
    }
}
