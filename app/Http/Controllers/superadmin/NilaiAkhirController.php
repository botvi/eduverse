<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\NilaiQuiz;
use App\Models\NilaiUjian;
use App\Models\Ujian;
use RealRashid\SweetAlert\Facades\Alert;

class NilaiAkhirController extends Controller
{
    /**
     * Hitung nilai akhir per siswa per mapel.
     * Formula baru:
     *   Rata-rata Quiz × 20% + Absensi × 10% + Sikap × 10% + UTS × 30% + UAS × 30%
     */
    public function index(Request $request)
    {
        $kelasFilter  = $request->input('kelas');
        $mapelFilter  = $request->input('mapel_id');
        $tahunFilter  = $request->input('tahun_ajaran');

        $mapels = Mapel::orderBy('kelas')->orderBy('nama_mapel')->get();

        // Ambil semua siswa (filter kelas & tahun ajaran jika ada)
        $siswaQuery = Siswa::with('user');
        if ($kelasFilter) {
            $siswaQuery->where('kelas', $kelasFilter);
        }
        if ($tahunFilter) {
            $siswaQuery->where('tahun_ajaran', $tahunFilter);
        }
        $siswas = $siswaQuery->orderBy('kelas')->orderBy('nama_lengkap')->get();

        $hasil = [];

        foreach ($siswas as $siswa) {
            $user_id = $siswa->user_id;

            // Mapel yang relevan dengan kelas siswa
            $mapelQuery = Mapel::where('kelas', $siswa->kelas);
            if ($mapelFilter) {
                $mapelQuery->where('id', $mapelFilter);
            }
            $mapelSiswa = $mapelQuery->get();

            foreach ($mapelSiswa as $mapel) {
                // --- Rata-rata Quiz ---
                $materiIds = Materi::where('mapel_id', $mapel->id)->pluck('id');

                $quizRecords = NilaiQuiz::where('user_id', $user_id)
                    ->whereIn('materi_id', $materiIds)
                    ->get();

                $nilaiQuizzes = $quizRecords->pluck('nilai_quiz');
                $quizAdaRemedial = $quizRecords->contains('is_remedial', true);

                $rataQuiz = $nilaiQuizzes->count() > 0
                    ? round($nilaiQuizzes->avg(), 2)
                    : null;

                // --- Nilai Absensi & Sikap (dari data siswa) ---
                $nilaiAbsensi = $siswa->nilai_absensi;
                $nilaiSikap   = $siswa->nilai_sikap;

                // --- Nilai UTS ---
                $ujianUTS = Ujian::where('mapel_id', $mapel->id)
                    ->whereRaw('LOWER(judul) LIKE ?', ['%uts%'])
                    ->first();

                $nilaiUTS = null;
                $utsAdaRemedial = false;
                if ($ujianUTS) {
                    $rec = NilaiUjian::where('user_id', $user_id)
                        ->where('ujian_id', $ujianUTS->id)
                        ->first();
                    $nilaiUTS = $rec ? $rec->nilai_ujian : null;
                    $utsAdaRemedial = $rec ? (bool) $rec->is_remedial : false;
                }

                // --- Nilai UAS ---
                $ujianUAS = Ujian::where('mapel_id', $mapel->id)
                    ->whereRaw('LOWER(judul) LIKE ?', ['%uas%'])
                    ->first();

                $nilaiUAS = null;
                $uasAdaRemedial = false;
                if ($ujianUAS) {
                    $rec = NilaiUjian::where('user_id', $user_id)
                        ->where('ujian_id', $ujianUAS->id)
                        ->first();
                    $nilaiUAS = $rec ? $rec->nilai_ujian : null;
                    $uasAdaRemedial = $rec ? (bool) $rec->is_remedial : false;
                }

                // --- Nilai Akhir ---
                // Formula: Quiz(20%) + Absensi(10%) + Sikap(10%) + UTS(30%) + UAS(30%)
                $nilaiAkhir = null;
                if ($rataQuiz !== null || $nilaiUTS !== null || $nilaiUAS !== null || $nilaiAbsensi !== null || $nilaiSikap !== null) {
                    $q   = $rataQuiz    ?? 0;
                    $abs = $nilaiAbsensi ?? 0;
                    $sik = $nilaiSikap  ?? 0;
                    $uts = $nilaiUTS    ?? 0;
                    $uas = $nilaiUAS    ?? 0;
                    $nilaiAkhir = round(($q * 0.20) + ($abs * 0.10) + ($sik * 0.10) + ($uts * 0.30) + ($uas * 0.30), 2);
                }

                $hasil[] = [
                    'siswa'          => $siswa,
                    'mapel'          => $mapel,
                    'rata_quiz'      => $rataQuiz,
                    'nilai_absensi'  => $nilaiAbsensi,
                    'nilai_sikap'    => $nilaiSikap,
                    'nilai_uts'      => $nilaiUTS,
                    'nilai_uas'      => $nilaiUAS,
                    'nilai_akhir'    => $nilaiAkhir,
                    'lulus'          => $nilaiAkhir !== null && $nilaiAkhir >= 72,
                    'ada_remedial'   => $quizAdaRemedial || $utsAdaRemedial || $uasAdaRemedial,
                ];
            }
        }

        return view('pagesuperadmin.nilaiakhirsiswa.rekap', compact('hasil', 'mapels', 'kelasFilter', 'mapelFilter'));
    }

    /**
     * Form input nilai absensi & sikap siswa
     */
    public function inputNilai(Request $request)
    {
        $kelasFilter = $request->input('kelas');

        $siswaQuery = Siswa::with('user');
        if ($kelasFilter) {
            $siswaQuery->where('kelas', $kelasFilter);
        }
        $siswas = $siswaQuery->orderBy('kelas')->orderBy('nama_lengkap')->get();

        return view('pagesuperadmin.nilaiakhirsiswa.input_nilai', compact('siswas', 'kelasFilter'));
    }

    /**
     * Simpan nilai absensi & sikap siswa (bulk update)
     */
    public function simpanNilai(Request $request)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.nilai_absensi' => 'nullable|integer|min:0|max:100',
            'nilai.*.nilai_sikap'   => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->nilai as $siswaId => $nilaiData) {
            $siswa = Siswa::find($siswaId);
            if ($siswa) {
                $siswa->update([
                    'nilai_absensi' => $nilaiData['nilai_absensi'] ?? null,
                    'nilai_sikap'   => $nilaiData['nilai_sikap']   ?? null,
                ]);
            }
        }

        Alert::success('Berhasil', 'Nilai absensi dan sikap berhasil disimpan!');
        return redirect()->route('nilai-akhir.input-nilai', ['kelas' => $request->input('kelas_filter')]);
    }

    /**
     * Halaman cetak nilai akhir
     */
    public function print(Request $request)
    {
        $kelasFilter = $request->input('kelas');
        $mapelFilter = $request->input('mapel_id');

        $siswaQuery = Siswa::with('user');
        if ($kelasFilter) {
            $siswaQuery->where('kelas', $kelasFilter);
        }
        $siswas = $siswaQuery->orderBy('kelas')->orderBy('nama_lengkap')->get();

        $hasil = [];

        foreach ($siswas as $siswa) {
            $user_id = $siswa->user_id;

            $mapelQuery = Mapel::where('kelas', $siswa->kelas);
            if ($mapelFilter) {
                $mapelQuery->where('id', $mapelFilter);
            }
            $mapelSiswa = $mapelQuery->get();

            foreach ($mapelSiswa as $mapel) {
                $materiIds = Materi::where('mapel_id', $mapel->id)->pluck('id');

                $quizRecords = NilaiQuiz::where('user_id', $user_id)
                    ->whereIn('materi_id', $materiIds)
                    ->get();

                $nilaiQuizzes = $quizRecords->pluck('nilai_quiz');
                $quizAdaRemedial = $quizRecords->contains('is_remedial', true);

                $rataQuiz = $nilaiQuizzes->count() > 0 ? round($nilaiQuizzes->avg(), 2) : null;

                $nilaiAbsensi = $siswa->nilai_absensi;
                $nilaiSikap   = $siswa->nilai_sikap;

                $ujianUTS = Ujian::where('mapel_id', $mapel->id)->whereRaw('LOWER(judul) LIKE ?', ['%uts%'])->first();
                $nilaiUTS = null;
                $utsAdaRemedial = false;
                if ($ujianUTS) {
                    $rec = NilaiUjian::where('user_id', $user_id)->where('ujian_id', $ujianUTS->id)->first();
                    $nilaiUTS = $rec ? $rec->nilai_ujian : null;
                    $utsAdaRemedial = $rec ? (bool) $rec->is_remedial : false;
                }

                $ujianUAS = Ujian::where('mapel_id', $mapel->id)->whereRaw('LOWER(judul) LIKE ?', ['%uas%'])->first();
                $nilaiUAS = null;
                $uasAdaRemedial = false;
                if ($ujianUAS) {
                    $rec = NilaiUjian::where('user_id', $user_id)->where('ujian_id', $ujianUAS->id)->first();
                    $nilaiUAS = $rec ? $rec->nilai_ujian : null;
                    $uasAdaRemedial = $rec ? (bool) $rec->is_remedial : false;
                }

                $nilaiAkhir = null;
                if ($rataQuiz !== null || $nilaiUTS !== null || $nilaiUAS !== null || $nilaiAbsensi !== null || $nilaiSikap !== null) {
                    $q   = $rataQuiz    ?? 0;
                    $abs = $nilaiAbsensi ?? 0;
                    $sik = $nilaiSikap  ?? 0;
                    $uts = $nilaiUTS    ?? 0;
                    $uas = $nilaiUAS    ?? 0;
                    $nilaiAkhir = round(($q * 0.20) + ($abs * 0.10) + ($sik * 0.10) + ($uts * 0.30) + ($uas * 0.30), 2);
                }

                $hasil[] = [
                    'siswa'         => $siswa,
                    'mapel'         => $mapel,
                    'rata_quiz'     => $rataQuiz,
                    'nilai_absensi' => $nilaiAbsensi,
                    'nilai_sikap'   => $nilaiSikap,
                    'nilai_uts'     => $nilaiUTS,
                    'nilai_uas'     => $nilaiUAS,
                    'nilai_akhir'   => $nilaiAkhir,
                    'lulus'         => $nilaiAkhir !== null && $nilaiAkhir >= 72,
                    'ada_remedial'  => $quizAdaRemedial || $utsAdaRemedial || $uasAdaRemedial,
                ];
            }
        }

        return view('pagesuperadmin.nilaiakhirsiswa.print_akhir', compact('hasil', 'kelasFilter', 'mapelFilter'));
    }
}
