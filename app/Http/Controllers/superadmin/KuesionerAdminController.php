<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KuesionerAdminController extends Controller
{
    /**
     * Rekap hasil kuesioner semua siswa
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

        // Filter berdasarkan kelas/tahun ajaran siswa
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

        $kelasList = ['VII A', 'VII B', 'VII C', 'VIII A', 'VIII B', 'VIII C', 'IX A', 'IX B', 'IX C'];
        $tahunList = ['2023-2024', '2024-2025', '2025-2026', '2026-2027', '2027-2028'];

        return view('pagesuperadmin.kuesioner.index', compact(
            'kuesioners', 'kelasFilter', 'jenisFilter', 'tahunFilter',
            'rataPreTest', 'rataPostTest', 'kelasList', 'tahunList'
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
     * Hapus satu record kuesioner
     */
    public function destroy(Kuesioner $kuesioner)
    {
        $kuesioner->delete();
        return back()->with('success', 'Data kuesioner dihapus.');
    }
}
