<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $totalRecords = 100;
        $batchSize    = 10;

        for ($i = 0; $i < $totalRecords / $batchSize; $i++) {
            Admin::factory($batchSize)->create();
        }
    }
}
