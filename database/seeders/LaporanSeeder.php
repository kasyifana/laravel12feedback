<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create laporan with random data
        Laporan::factory()->count(10)->create();
        
        // Create laporan with specific status
        Laporan::factory()->count(5)->create([
            'status' => 'Pending',
            'respon' => null,
            'oleh' => null,
            'waktu_respon' => null,
        ]);
        
        Laporan::factory()->count(3)->create([
            'status' => 'In Progress',
        ]);
        
        Laporan::factory()->count(2)->create([
            'status' => 'Selesai',
        ]);
    }
}
