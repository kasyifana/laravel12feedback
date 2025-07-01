<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Laporan>
 */
class LaporanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['Pending', 'In Progress', 'Selesai']);
        $tanggalLapor = $this->faker->dateTimeBetween('-1 month', 'now');
        $waktuLapor = $this->faker->time('H:i:s');
        
        // Randomly decide whether to associate with a user or make it anonymous
        $userIds = User::pluck('id')->toArray();
        $userId = count($userIds) > 0 && $this->faker->boolean(70) ? $this->faker->randomElement($userIds) : null;
        $namaPelapor = $userId ? User::find($userId)->name : $this->faker->name();
        
        return [
            'user_id' => $userId,
            'judul' => $this->faker->sentence(4),
            'kategori' => $this->faker->randomElement(['Teknis', 'Administrasi', 'Fasilitas', 'Layanan', 'Lainnya']),
            'deskripsi' => $this->faker->paragraph(3),
            'prioritas' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'status' => $status,
            'tanggal_lapor' => $tanggalLapor->format('Y-m-d'),
            'waktu_lapor' => $waktuLapor,
            'nama_pelapor' => $namaPelapor,
            'lampiran' => null,
            'respon' => $status !== 'Pending' ? $this->faker->paragraph(2) : null,
            'oleh' => $status !== 'Pending' ? $this->faker->name() : null,
            'waktu_respon' => $status !== 'Pending' ? $this->faker->dateTimeBetween($tanggalLapor, 'now') : null,
        ];
    }
}
