<?php

namespace App\Http\Controllers\superadmin;

use App\Models\NilaiQuiz;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;

class NilaiQuizController extends Controller
{
    public function index(Request $request)
    {
        $kelasFilter = $request->input('kelas');
        $mapelFilter = $request->input('mapel_id');
        $tanggalFilter = $request->input('tanggal');

        $query = NilaiQuiz::with(['user', 'user.siswa', 'materi', 'materi.mapel'])->latest();

        // Filter berdasarkan kelas siswa
        if ($kelasFilter) {
            $query->whereHas('user.siswa', function ($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }

        // Filter berdasarkan mapel
        if ($mapelFilter) {
            $query->whereHas('materi', function ($q) use ($mapelFilter) {
                $q->where('mapel_id', $mapelFilter);
            });
        }

        // Filter berdasarkan tanggal
        if ($tanggalFilter) {
            $query->whereDate('created_at', $tanggalFilter);
        }

        $nilais = $query->get();
        $mapels = \App\Models\Mapel::orderBy('kelas')->orderBy('nama_mapel')->get();

        // Ambil kelas unik dari daftar siswa untuk dropdown
        $kelasList = \App\Models\Siswa::distinct()->orderBy('kelas')->pluck('kelas');

        return view('pagesuperadmin.nilai_quiz.index', compact('nilais', 'mapels', 'kelasList', 'kelasFilter', 'mapelFilter', 'tanggalFilter'));
    }

    public function destroy(NilaiQuiz $nilaiQuiz)
    {
        $nilaiQuiz->delete();
        Alert::success('Success', 'Nilai Quiz berhasil dihapus');
        return back();
    }

    public function print(Request $request)
    {
        $kelasFilter = $request->input('kelas');
        $mapelFilter = $request->input('mapel_id');
        $tanggalFilter = $request->input('tanggal');

        $query = NilaiQuiz::with(['user', 'user.siswa', 'materi', 'materi.mapel'])->latest();

        // Filter berdasarkan kelas siswa
        if ($kelasFilter) {
            $query->whereHas('user.siswa', function ($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }

        // Filter berdasarkan mapel
        if ($mapelFilter) {
            $query->whereHas('materi', function ($q) use ($mapelFilter) {
                $q->where('mapel_id', $mapelFilter);
            });
        }

        // Filter berdasarkan tanggal
        if ($tanggalFilter) {
            $query->whereDate('created_at', $tanggalFilter);
        }

        $nilais = $query->get();
        return view('pagesuperadmin.nilai_quiz.print', compact('nilais', 'kelasFilter', 'mapelFilter', 'tanggalFilter'));
    }
}
