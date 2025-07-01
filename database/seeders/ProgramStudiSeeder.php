<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudi = [
            ['code' => 'd3_analisis_kimia', 'name' => 'Analisis Kimia D3'],
            ['code' => 'st_akuntansi_perpajakan', 'name' => 'Akuntansi Perpajakan Terapan'],
            ['code' => 'st_analisis_keuangan', 'name' => 'Analisis Keuangan Terapan'],
            ['code' => 'st_bisnis_digital', 'name' => 'Bisnis Digital Terapan'],
            ['code' => 's1_akuntansi', 'name' => 'Akuntansi S1'],
            ['code' => 's1_arsitektur', 'name' => 'Arsitektur S1'],
            ['code' => 's1_ekonomi_islam', 'name' => 'Ekonomi Islam S1'],
            ['code' => 's1_ekonomi_pembangunan', 'name' => 'Ekonomi Pembangunan S1'],
            ['code' => 's1_farmasi', 'name' => 'Farmasi S1'],
            ['code' => 's1_hubungan_internasional', 'name' => 'Hubungan Internasional S1'],
            ['code' => 's1_hukum_keluarga', 'name' => 'Hukum Keluarga/Ahwal Syakhshiyah S1'],
            ['code' => 's1_hukum', 'name' => 'Hukum S1'],
            ['code' => 's1_hukum_bisnis', 'name' => 'Hukum Bisnis S1'],
            ['code' => 's1_ilmu_komunikasi', 'name' => 'Ilmu Komunikasi S1'],
            ['code' => 's1_kedokteran', 'name' => 'Kedokteran S1'],
            ['code' => 's1_informatika', 'name' => 'Informatika S1'],
            ['code' => 's1_manajemen', 'name' => 'Manajemen S1'],
            ['code' => 's1_pendidikan_agama_islam', 'name' => 'Pendidikan Agama Islam S1'],
            ['code' => 's1_pendidikan_bahasa_inggris', 'name' => 'Pendidikan Bahasa Inggris S1'],
            ['code' => 's1_pendidikan_kimia', 'name' => 'Pendidikan Kimia S1'],
            ['code' => 's1_psikologi', 'name' => 'Psikologi S1'],
            ['code' => 's1_kimia', 'name' => 'Kimia S1'],
            ['code' => 's1_statistika', 'name' => 'Statistika S1'],
            ['code' => 's1_rekayasa_tekstil', 'name' => 'Rekayasa Tekstil S1'],
            ['code' => 's1_manajemen_rekayasa', 'name' => 'Manajemen Rekayasa S1'],
            ['code' => 's1_teknik_elektro', 'name' => 'Teknik Elektro S1'],
            ['code' => 's1_teknik_industri', 'name' => 'Teknik Industri S1'],
            ['code' => 's1_teknik_kimia', 'name' => 'Teknik Kimia S1'],
            ['code' => 's1_teknik_lingkungan', 'name' => 'Teknik Lingkungan S1'],
            ['code' => 's1_teknik_mesin', 'name' => 'Teknik Mesin S1'],
            ['code' => 's1_teknik_sipil', 'name' => 'Teknik Sipil S1'],
            ['code' => 's2_akuntansi', 'name' => 'Akuntansi S2'],
            ['code' => 's2_arsitektur', 'name' => 'Arsitektur S2'],
            ['code' => 's2_farmasi', 'name' => 'Farmasi S2'],
            ['code' => 's2_hukum', 'name' => 'Hukum S2'],
            ['code' => 's2_kenotariatan', 'name' => 'Kenotariatan S2'],
            ['code' => 's2_manajemen', 'name' => 'Manajemen S2'],
            ['code' => 's2_ilmu_agama_islam', 'name' => 'Ilmu Agama Islam S2'],
            ['code' => 's2_ilmu_ekonomi', 'name' => 'Ilmu Ekonomi S2'],
            ['code' => 's2_statistika', 'name' => 'Statistika S2'],
            ['code' => 's2_kesehatan_masyarakat', 'name' => 'Kesehatan Masyarakat S2'],
            ['code' => 's2_informatika', 'name' => 'Informatika S2'],
            ['code' => 's2_rekayasa_elektro', 'name' => 'Rekayasa Elektro S2'],
            ['code' => 's2_teknik_industri', 'name' => 'Teknik Industri S2'],
            ['code' => 's2_teknik_kimia', 'name' => 'Teknik Kimia S2'],
            ['code' => 's2_teknik_lingkungan', 'name' => 'Teknik Lingkungan S2'],
            ['code' => 's2_teknik_sipil', 'name' => 'Teknik Sipil S2'],
            ['code' => 's2_kimia', 'name' => 'Kimia S2'],
            ['code' => 's2_psikologi', 'name' => 'Psikologi S2'],
            ['code' => 's2_ilmu_komunikasi', 'name' => 'Ilmu Komunikasi S2'],
            ['code' => 's3_hukum', 'name' => 'Hukum S3'],
            ['code' => 's3_hukum_islam', 'name' => 'Hukum Islam S3'],
            ['code' => 's3_ilmu_ekonomi', 'name' => 'Ilmu Ekonomi S3'],
            ['code' => 's3_manajemen', 'name' => 'Manajemen S3'],
            ['code' => 's3_teknik_sipil', 'name' => 'Teknik Sipil S3'],
            ['code' => 's3_rekayasa_industri', 'name' => 'Rekayasa Industri S3'],
            ['code' => 'prof_arsitek', 'name' => 'Arsitek Profesi'],
            ['code' => 'prof_dokter', 'name' => 'Dokter Profesi'],
            ['code' => 'prof_apoteker', 'name' => 'Apoteker Profesi'],
            ['code' => 'prof_psikologi', 'name' => 'Psikologi Profesi']
        ];

        DB::table('program_studi')->insert($programStudi);
    }
}
