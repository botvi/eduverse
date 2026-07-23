<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuesionerController extends Controller
{
    /**
     * Tampilkan form kuesioner (pre_test atau post_test)
     */
    public function show($jenis)
    {
        if (!in_array($jenis, ['pre_test', 'post_test'])) {
            abort(404);
        }

        $userId = Auth::id();
        $sudahIsi = Kuesioner::where('user_id', $userId)->where('jenis', $jenis)->exists();

        $labelJenis = $jenis === 'pre_test' ? 'Pre-Test' : 'Post-Test';

        return view('pageuser.kuesioner.form', compact('jenis', 'labelJenis', 'sudahIsi'));
    }

    /**
     * Simpan jawaban kuesioner
     */
    public function store(Request $request, $jenis)
    {
        if (!in_array($jenis, ['pre_test', 'post_test'])) {
            abort(404);
        }

        $request->validate([
            'jawaban'   => 'required|array|min:10|max:10',
            'jawaban.*' => 'required|integer|min:1|max:5',
        ]);

        $userId = Auth::id();

        // Cek apakah sudah pernah isi
        if (Kuesioner::where('user_id', $userId)->where('jenis', $jenis)->exists()) {
            return redirect()->route('user.kuesioner.show', $jenis)
                ->with('error', 'Anda sudah mengisi kuesioner ini sebelumnya.');
        }

        $skor = Kuesioner::hitungSkor($request->jawaban);

        Kuesioner::create([
            'user_id'    => $userId,
            'jenis'      => $jenis,
            'jawaban'    => $request->jawaban,
            'skor_total' => $skor,
        ]);

        $label = $jenis === 'pre_test' ? 'Pre-Test' : 'Post-Test';

        return redirect()->route('index')
            ->with('success', "Kuesioner {$label} berhasil disimpan! Skor Anda: {$skor}");
    }
}
