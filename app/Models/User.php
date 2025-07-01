<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'programStudy',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Get the human readable name for the program study code
     */
    public function getProgramStudyNameAttribute(): string
    {
        $programStudyNames = [
            'd3_analisis_kimia' => 'Analisis Kimia D3',
            'st_akuntansi_perpajakan' => 'Akuntansi Perpajakan Terapan',
            'st_analisis_keuangan' => 'Analisis Keuangan Terapan',
            'st_bisnis_digital' => 'Bisnis Digital Terapan',
            's1_akuntansi' => 'Akuntansi S1',
            's1_arsitektur' => 'Arsitektur S1',
            's1_ekonomi_islam' => 'Ekonomi Islam S1',
            's1_ekonomi_pembangunan' => 'Ekonomi Pembangunan S1',
            's1_farmasi' => 'Farmasi S1',
            's1_hubungan_internasional' => 'Hubungan Internasional S1',
            's1_hukum_keluarga' => 'Hukum Keluarga/Ahwal Syakhshiyah S1',
            's1_hukum' => 'Hukum S1',
            's1_hukum_bisnis' => 'Hukum Bisnis S1',
            's1_ilmu_komunikasi' => 'Ilmu Komunikasi S1',
            's1_kedokteran' => 'Kedokteran S1',
            's1_informatika' => 'Informatika S1',
            's1_manajemen' => 'Manajemen S1',
            's1_pendidikan_agama_islam' => 'Pendidikan Agama Islam S1',
            's1_pendidikan_bahasa_inggris' => 'Pendidikan Bahasa Inggris S1',
            's1_pendidikan_kimia' => 'Pendidikan Kimia S1',
            's1_psikologi' => 'Psikologi S1',
            's1_kimia' => 'Kimia S1',
            's1_statistika' => 'Statistika S1',
            's1_rekayasa_tekstil' => 'Rekayasa Tekstil S1',
            's1_manajemen_rekayasa' => 'Manajemen Rekayasa S1',
            's1_teknik_elektro' => 'Teknik Elektro S1',
            's1_teknik_industri' => 'Teknik Industri S1',
            's1_teknik_kimia' => 'Teknik Kimia S1',
            's1_teknik_lingkungan' => 'Teknik Lingkungan S1',
            's1_teknik_mesin' => 'Teknik Mesin S1',
            's1_teknik_sipil' => 'Teknik Sipil S1',
            's2_akuntansi' => 'Akuntansi S2',
            's2_arsitektur' => 'Arsitektur S2',
            's2_farmasi' => 'Farmasi S2',
            's2_hukum' => 'Hukum S2',
            's2_kenotariatan' => 'Kenotariatan S2',
            's2_manajemen' => 'Manajemen S2',
            's2_ilmu_agama_islam' => 'Ilmu Agama Islam S2',
            's2_ilmu_ekonomi' => 'Ilmu Ekonomi S2',
            's2_statistika' => 'Statistika S2',
            's2_kesehatan_masyarakat' => 'Kesehatan Masyarakat S2',
            's2_informatika' => 'Informatika S2',
            's2_rekayasa_elektro' => 'Rekayasa Elektro S2',
            's2_teknik_industri' => 'Teknik Industri S2',
            's2_teknik_kimia' => 'Teknik Kimia S2',
            's2_teknik_lingkungan' => 'Teknik Lingkungan S2',
            's2_teknik_sipil' => 'Teknik Sipil S2',
            's2_kimia' => 'Kimia S2',
            's2_psikologi' => 'Psikologi S2',
            's2_ilmu_komunikasi' => 'Ilmu Komunikasi S2',
            's3_hukum' => 'Hukum S3',
            's3_hukum_islam' => 'Hukum Islam S3',
            's3_ilmu_ekonomi' => 'Ilmu Ekonomi S3',
            's3_manajemen' => 'Manajemen S3',
            's3_teknik_sipil' => 'Teknik Sipil S3',
            's3_rekayasa_industri' => 'Rekayasa Industri S3',
            'prof_arsitek' => 'Arsitek Profesi',
            'prof_dokter' => 'Dokter Profesi',
            'prof_apoteker' => 'Apoteker Profesi',
            'prof_psikologi' => 'Psikologi Profesi',
        ];

        return $programStudyNames[$this->programStudy] ?? $this->programStudy;
    }

    /**
     * Get the user's feedback
     */
    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }
    
    /**
     * Get the user's laporan (reports)
     */
    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }
}
