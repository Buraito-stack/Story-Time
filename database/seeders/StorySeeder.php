<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        $totalRecords = 1000;
        $batchSize    = 10;

        for ($i = 0; $i < $totalRecords / $batchSize; $i++) {
            $users = User::inRandomOrder()->take($batchSize)->get();
            foreach ($users as $user) {
                $story = Story::factory()->create(['user_id' => $user->id]);
                $story->coverImage()->save(File::factory()->make());
            }
        }
    }
}
