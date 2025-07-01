<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $programStudiCodes = [
            'd3_analisis_kimia', 'st_akuntansi_perpajakan', 'st_analisis_keuangan', 'st_bisnis_digital',
            's1_akuntansi', 's1_arsitektur', 's1_ekonomi_islam', 's1_ekonomi_pembangunan', 's1_farmasi',
            's1_hubungan_internasional', 's1_hukum_keluarga', 's1_hukum', 's1_hukum_bisnis', 's1_ilmu_komunikasi',
            's1_kedokteran', 's1_informatika', 's1_manajemen', 's1_pendidikan_agama_islam', 's1_pendidikan_bahasa_inggris',
            's1_pendidikan_kimia', 's1_psikologi', 's1_kimia', 's1_statistika', 's1_rekayasa_tekstil',
            's1_manajemen_rekayasa', 's1_teknik_elektro', 's1_teknik_industri', 's1_teknik_kimia',
            's1_teknik_lingkungan', 's1_teknik_mesin', 's1_teknik_sipil', 's2_akuntansi', 's2_arsitektur',
            's2_farmasi', 's2_hukum', 's2_kenotariatan', 's2_manajemen', 's2_ilmu_agama_islam', 's2_ilmu_ekonomi',
            's2_statistika', 's2_kesehatan_masyarakat', 's2_informatika', 's2_rekayasa_elektro', 's2_teknik_industri',
            's2_teknik_kimia', 's2_teknik_lingkungan', 's2_teknik_sipil', 's2_kimia', 's2_psikologi',
            's2_ilmu_komunikasi', 's3_hukum', 's3_hukum_islam', 's3_ilmu_ekonomi', 's3_manajemen',
            's3_teknik_sipil', 's3_rekayasa_industri', 'prof_arsitek', 'prof_dokter', 'prof_apoteker', 'prof_psikologi'
        ];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'programStudy' => fake()->randomElement($programStudiCodes),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
