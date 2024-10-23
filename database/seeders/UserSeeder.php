<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\File;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $totalRecords = 230;
        $batchSize    = 10;

        for ($i = 0; $i < $totalRecords / $batchSize; $i++) {
            $users = User::factory($batchSize)->create();

            foreach ($users as $user) {
                $user->profilePicture()->save(File::factory()->make());
            }
        }
    }
}
