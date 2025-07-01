<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudiCodes = [
            's1_informatika', 's1_manajemen', 's1_akuntansi', 's2_manajemen', 's2_informatika'
        ];

        //create users with is_admin set to true, and false
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'programStudy' => $programStudiCodes[array_rand($programStudiCodes)],
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
            'programStudy' => $programStudiCodes[array_rand($programStudiCodes)],
        ]);
    }
}
