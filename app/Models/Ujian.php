<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapel_id',
        'judul',
        'soal',
        'status',
        'jam_mulai',
        'jam_selesai',
        'jenis_ujian',
    ];

    protected $casts = [
        'soal' => 'array',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function nilaiUjians()
    {
        return $this->hasMany(NilaiUjian::class);
    }
}
