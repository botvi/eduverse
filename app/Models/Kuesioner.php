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

    /**
     * Dapatkan daftar pertanyaan angket kuesioner sesuai jenis (pre_test atau post_test)
     */
    public static function getPertanyaan(string $jenis): array
    {
        if ($jenis === 'pre_test') {
            return [
                1  => 'Saya merasa senang dan bersemangat dalam mempelajari materi pelajaran ini.',
                2  => 'Saya tertarik belajar jika menggunakan media digital atau teknologi interaktif.',
                3  => 'Saya merasa cepat bosan atau kurang fokus saat belajar menggunakan buku teks biasa.',
                4  => 'Saya aktif mencari sumber belajar tambahan di luar penjelasan guru di kelas.',
                5  => 'Saya membutuhkan media pembelajaran interaktif untuk membantu memahami materi.',
                6  => 'Saya merasa percaya diri dapat menguasai materi pelajaran ini dengan baik.',
                7  => 'Saya berminat menggunakan media pembelajaran berbasis web untuk kegiatan belajar.',
                8  => 'Saya rutin mengulang dan melatih pemahaman materi pelajaran secara mandiri.',
                9  => 'Saya merasa pembelajaran berbasis teknologi dapat membuat suasana belajar lebih menyenangkan.',
                10 => 'Saya berminat memanfaatkan platform digital jika disediakan untuk membantu belajar saya.',
            ];
        }

        return [
            1  => 'Saya merasa senang dan lebih bersemangat belajar setelah menggunakan aplikasi web ini.',
            2  => 'Pembelajaran dengan aplikasi web ini membuat saya lebih tertarik dibanding buku konvensional.',
            3  => 'Tampilan dan fitur interaktif pada aplikasi web ini membuat saya tidak cepat bosan saat belajar.',
            4  => 'Materi dan pembahasan dalam aplikasi web ini membantu saya memahami pelajaran dengan lebih mudah.',
            5  => 'Game dan kuis interaktif pada aplikasi web ini memotivasi saya untuk belajar lebih giat.',
            6  => 'Saya merasa lebih percaya diri terhadap pemahaman materi setelah belajar menggunakan aplikasi ini.',
            7  => 'Saya menjadi lebih aktif mengerjakan materi dan latihan yang ada di aplikasi web ini.',
            8  => 'Saya merasa aplikasi web ini memberikan pengalaman belajar digital yang sangat menyenangkan.',
            9  => 'Saya ingin terus memanfaatkan aplikasi web ini untuk mempelajari materi-materi selanjutnya.',
            10 => 'Saya merekomendasikan aplikasi web pembelajaran ini kepada teman-teman saya.',
        ];
    }
}
