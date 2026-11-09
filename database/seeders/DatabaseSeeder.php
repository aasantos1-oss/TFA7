<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Validate the one-user requirement before inserting or updating sample tasks.
            $this->call([
                ProfileSeeder::class,
                TaskSeeder::class,
            ]);
        });
    }
}
