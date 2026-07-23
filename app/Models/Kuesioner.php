<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuesioner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis',
        'jawaban',
        'skor_total',
    ];

    protected $casts = [
        'jawaban' => 'array',
        'skor_total' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hitung skor total dari jawaban (skala 1-5, 10 soal → maks 50 → konversi ke 100)
     */
    public static function hitungSkor(array $jawaban): float
    {
        $total = array_sum($jawaban);
        $max   = count($jawaban) * 5;
        return $max > 0 ? round(($total / $max) * 100, 2) : 0;
    }
}
