<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // Call the UsersSeeder to create initial users
        $this->call(UsersSeeder::class);

        // Call the FeedbackSeeder to create sample feedback
        $this->call(FeedbackSeeder::class);
        
        // Call the ProgramStudiSeeder to create program studi data
        $this->call(ProgramStudiSeeder::class);
        
        // Call the LaporanSeeder to create sample laporan data
        $this->call(LaporanSeeder::class);
    }
}
