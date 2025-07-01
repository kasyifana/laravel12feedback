<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    // Set the table name
    protected $table = 'laporan';
    
    // Set the primary key
    protected $primaryKey = 'id_laporan';
    
    // Set which fields can be mass assigned
    protected $fillable = [
        'user_id',
        'judul',
        'kategori',
        'deskripsi',
        'prioritas',
        'status',
        'tanggal_lapor',
        'waktu_lapor',
        'nama_pelapor',
        'lampiran',
        'respon',
        'oleh',
        'waktu_respon',
    ];
    
    // Set proper casting for fields
    protected $casts = [
        'prioritas' => 'string',
        'status' => 'string',
        'tanggal_lapor' => 'date',
        'waktu_lapor' => 'datetime',
        'waktu_respon' => 'datetime',
    ];
    
    /**
     * Get the user that owns the laporan
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
