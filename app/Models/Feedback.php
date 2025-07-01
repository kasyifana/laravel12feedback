<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'rating',
        'komentar',
        'balasan',
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk feedback yang memiliki balasan
     */
    public function scopeWithReplies($query)
    {
        return $query->whereNotNull('balasan');
    }

    /**
     * Scope untuk feedback tanpa balasan
     */
    public function scopeWithoutReplies($query)
    {
        return $query->whereNull('balasan');
    }

    /**
     * Scope untuk filter berdasarkan rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}
