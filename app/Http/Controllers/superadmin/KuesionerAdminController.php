<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner;
use App\Models\User;
use Illuminate\Http\Request;

class KuesionerAdminController extends Controller
{
    /**
     * Rekap hasil kuesioner semua siswa & Analisis Peningkatan Minat Belajar
     */
    public function index(Request $request)
    {
        $kelasFilter = $request->input('kelas');
        $jenisFilter = $request->input('jenis');
        $tahunFilter = $request->input('tahun_ajaran');

        $query = Kuesioner::with('user.siswa')->latest();

        if ($jenisFilter) {
            $query->where('jenis', $jenisFilter);
        }

        if ($kelasFilter || $tahunFilter) {
            $query->whereHas('user.siswa', function ($q) use ($kelasFilter, $tahunFilter) {
                if ($kelasFilter) $q->where('kelas', $kelasFilter);
                if ($tahunFilter) $q->where('tahun_ajaran', $tahunFilter);
            });
        }

        $kuesioners = $query->get();

        // Hitung rata-rata skor per jenis
        $rataPreTest  = Kuesioner::where('jenis', 'pre_test')->avg('skor_total');
        $rataPostTest = Kuesioner::where('jenis', 'post_test')->avg('skor_total');

        // Analisis Perbandingan Peningkatan Minat Belajar Siswa
        $analisisSiswa = $this->getAnalisisPeningkatan($kelasFilter, $tahunFilter);

        $kelasList = ['VII A', 'VII B', 'VII C', 'VIII A', 'VIII B', 'VIII C', 'IX A', 'IX B', 'IX C'];
        $tahunList = ['2023-2024', '2024-2025', '2025-2026', '2026-2027', '2027-2028'];

        return view('pagesuperadmin.kuesioner.index', compact(
            'kuesioners', 'kelasFilter', 'jenisFilter', 'tahunFilter',
            'rataPreTest', 'rataPostTest', 'analisisSiswa', 'kelasList', 'tahunList'
        ));
    }

    /**
     * Detail jawaban satu kuesioner
     */
    public function show(Kuesioner $kuesioner)
    {
        $kuesioner->load('user.siswa');
        return view('pagesuperadmin.kuesioner.show', compact('kuesioner'));
    }

    /**
     * Cetak Laporan Rekap Kuesioner & Peningkatan Siswa
     */
    public function print(Request $request)
    {
        $kelasFilter = $request->input('kelas');
        $tahunFilter = $request->input('tahun_ajaran');

        $analisisSiswa = $this->getAnalisisPeningkatan($kelasFilter, $tahunFilter);

        $rataPreTest  = Kuesioner::where('jenis', 'pre_test')->avg('skor_total');
        $rataPostTest = Kuesioner::where('jenis', 'post_test')->avg('skor_total');

        $kuesioners = Kuesioner::with('user.siswa')->latest()->get();

        return view('pagesuperadmin.kuesioner.print', compact(
            'analisisSiswa', 'rataPreTest', 'rataPostTest', 'kelasFilter', 'tahunFilter', 'kuesioners'
        ));
    }

    /**
     * Hapus satu record kuesioner
     */
    public function destroy(Kuesioner $kuesioner)
    {
        $kuesioner->delete();
        return back()->with('success', 'Data kuesioner berhasil dihapus.');
    }

    /**
     * Helper untuk menghitung perbandingan & peningkatan minat belajar per siswa
     */
    private function getAnalisisPeningkatan($kelasFilter = null, $tahunFilter = null)
    {
        $queryUsers = User::whereHas('kuesioners');

        if ($kelasFilter || $tahunFilter) {
            $queryUsers->whereHas('siswa', function ($q) use ($kelasFilter, $tahunFilter) {
                if ($kelasFilter) $q->where('kelas', $kelasFilter);
                if ($tahunFilter) $q->where('tahun_ajaran', $tahunFilter);
            });
        }

        $users = $queryUsers->with(['siswa', 'kuesioners'])->get();

        $result = [];
        $totalPeningkatan = 0;
        $countLengkap = 0;
        $countMeningkat = 0;

        foreach ($users as $user) {
            $preKues  = $user->kuesioners->firstWhere('jenis', 'pre_test');
            $postKues = $user->kuesioners->firstWhere('jenis', 'post_test');

            $preSkor  = $preKues ? (float)$preKues->skor_total : null;
            $postSkor = $postKues ? (float)$postKues->skor_total : null;

            $peningkatan = ($preSkor !== null && $postSkor !== null) ? round($postSkor - $preSkor, 2) : null;
            $statusPeningkatan = '-';

            if ($preSkor !== null && $postSkor !== null) {
                if ($peningkatan >= 20) {
                    $statusPeningkatan = 'Sangat Meningkat';
                } elseif ($peningkatan > 0) {
                    $statusPeningkatan = 'Meningkat';
                } elseif ($peningkatan == 0) {
                    $statusPeningkatan = 'Tetap';
                } else {
                    $statusPeningkatan = 'Menurun';
                }

                if ($peningkatan > 0) {
                    $countMeningkat++;
                }

                $totalPeningkatan += $peningkatan;
                $countLengkap++;
            }

            $result[] = [
                'user'               => $user,
                'siswa'              => $user->siswa,
                'pre_skor'           => $preSkor,
                'post_skor'          => $postSkor,
                'peningkatan'        => $peningkatan,
                'status_peningkatan' => $statusPeningkatan,
            ];
        }

        $rataPeningkatan = $countLengkap > 0 ? round($totalPeningkatan / $countLengkap, 2) : null;
        $persenMeningkat = $countLengkap > 0 ? round(($countMeningkat / $countLengkap) * 100, 1) : 0;

        return [
            'data'             => $result,
            'count_lengkap'    => $countLengkap,
            'count_meningkat'  => $countMeningkat,
            'rata_peningkatan' => $rataPeningkatan,
            'persen_meningkat' => $persenMeningkat,
        ];
    }
}
