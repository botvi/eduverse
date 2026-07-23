<?php

namespace App\Http\Controllers\superadmin;

use App\Models\Ujian;
use App\Models\Mapel;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;

class UjianController extends Controller
{
    public function index()
    {
        $ujians = Ujian::with('mapel')->latest()->get();
        return view('pagesuperadmin.master_ujian.index', compact('ujians'));
    }

    public function create()
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        return view('pagesuperadmin.master_ujian.create', compact('mapels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mapel_ids'           => 'required|array|min:1',
            'mapel_ids.*'         => 'required|exists:mapels,id',
            'judul'               => 'required|string|max:255',
            'status'              => 'required|in:dimulai,belum dimulai',
            'jenis_ujian'         => 'required|in:UTS,UAS,Lainnya',
            'jam_mulai'           => 'nullable|date_format:H:i',
            'jam_selesai'         => 'nullable|date_format:H:i|after:jam_mulai',
            'pertanyaan'          => 'nullable|array',
            'a'                   => 'nullable|array',
            'b'                   => 'nullable|array',
            'c'                   => 'nullable|array',
            'd'                   => 'nullable|array',
            'jawaban'             => 'required|array',
            'gambar_pertanyaan'   => 'nullable|array',
            'gambar_pertanyaan.*' => 'nullable|image|max:2048',
            'gambar_a'            => 'nullable|array',
            'gambar_a.*'          => 'nullable|image|max:2048',
            'gambar_b'            => 'nullable|array',
            'gambar_b.*'          => 'nullable|image|max:2048',
            'gambar_c'            => 'nullable|array',
            'gambar_c.*'          => 'nullable|image|max:2048',
            'gambar_d'            => 'nullable|array',
            'gambar_d.*'          => 'nullable|image|max:2048',
        ], [
            'mapel_ids.required' => 'Pilih minimal satu mapel / kelas.',
            'jam_selesai.after'  => 'Jam selesai harus setelah jam mulai.',
        ]);

        // Proses semua soal + upload gambar sekali dulu
        $soal       = [];
        $fileFields = ['gambar_pertanyaan', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d'];
        $jawabanCount = count($request->jawaban ?? []);

        for ($i = 0; $i < $jawabanCount; $i++) {
            $item = [
                'pertanyaan' => $request->pertanyaan[$i] ?? null,
                'a'          => $request->a[$i] ?? null,
                'b'          => $request->b[$i] ?? null,
                'c'          => $request->c[$i] ?? null,
                'd'          => $request->d[$i] ?? null,
                'jawaban'    => $request->jawaban[$i] ?? null,
            ];

            foreach ($fileFields as $field) {
                if ($request->hasFile("{$field}.{$i}")) {
                    $file     = $request->file("{$field}.{$i}");
                    $filename = time() . "_{$field}_{$i}_" . $file->getClientOriginalName();
                    $file->move(public_path('uploads/ujians'), $filename);
                    $item[$field] = 'uploads/ujians/' . $filename;
                } else {
                    $item[$field] = null;
                }
            }
            $soal[] = $item;
        }

        // Buat 1 ujian per mapel yang dipilih, gambar di-copy untuk kelas ke-2 dst
        $mapelIds = $request->input('mapel_ids');
        $count    = 0;

        foreach ($mapelIds as $idx => $mapel_id) {
            $soalForThis = $soal;

            // Untuk kelas ke-2 dan seterusnya, salin file gambar agar path unik
            if ($idx > 0) {
                foreach ($soalForThis as &$item) {
                    foreach ($fileFields as $field) {
                        if (!empty($item[$field])) {
                            $originalPath = public_path($item[$field]);
                            if (file_exists($originalPath)) {
                                $newName = time() . "_{$idx}_" . basename($item[$field]);
                                $newPath = public_path('uploads/ujians/' . $newName);
                                copy($originalPath, $newPath);
                                $item[$field] = 'uploads/ujians/' . $newName;
                            }
                        }
                    }
                }
                unset($item);
            }

            Ujian::create([
                'mapel_id'    => $mapel_id,
                'judul'       => $request->judul,
                'status'      => $request->status,
                'jenis_ujian' => $request->jenis_ujian,
                'jam_mulai'   => $request->jam_mulai ?: null,
                'jam_selesai' => $request->jam_selesai ?: null,
                'soal'        => $soalForThis,
            ]);
            $count++;
        }

        Alert::success('Success', "Ujian berhasil ditambahkan untuk {$count} kelas.");
        return redirect()->route('ujian.index');
    }

    public function edit(Ujian $ujian)
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        return view('pagesuperadmin.master_ujian.edit', compact('ujian', 'mapels'));
    }

    public function update(Request $request, Ujian $ujian)
    {
        $request->validate([
            'mapel_ids'           => 'required|array|min:1',
            'mapel_ids.*'         => 'required|exists:mapels,id',
            'judul'               => 'required|string|max:255',
            'status'              => 'required|in:dimulai,belum dimulai',
            'jenis_ujian'         => 'required|in:UTS,UAS,Lainnya',
            'jam_mulai'           => 'nullable|date_format:H:i',
            'jam_selesai'         => 'nullable|date_format:H:i|after:jam_mulai',
            'pertanyaan'          => 'nullable|array',
            'a'                   => 'nullable|array',
            'b'                   => 'nullable|array',
            'c'                   => 'nullable|array',
            'd'                   => 'nullable|array',
            'jawaban'             => 'required|array',
            'gambar_pertanyaan'   => 'nullable|array',
            'gambar_pertanyaan.*' => 'nullable|image|max:2048',
            'gambar_a'            => 'nullable|array',
            'gambar_a.*'          => 'nullable|image|max:2048',
            'gambar_b'            => 'nullable|array',
            'gambar_b.*'          => 'nullable|image|max:2048',
            'gambar_c'            => 'nullable|array',
            'gambar_c.*'          => 'nullable|image|max:2048',
            'gambar_d'            => 'nullable|array',
            'gambar_d.*'          => 'nullable|image|max:2048',
        ], [
            'mapel_ids.required' => 'Pilih minimal satu mapel / kelas.',
            'jam_selesai.after'  => 'Jam selesai harus setelah jam mulai.',
        ]);

        $soal       = [];
        $fileFields = ['gambar_pertanyaan', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d'];
        $jawabanCount = count($request->jawaban ?? []);

        for ($i = 0; $i < $jawabanCount; $i++) {
            $item = [
                'pertanyaan' => $request->pertanyaan[$i] ?? null,
                'a'          => $request->a[$i] ?? null,
                'b'          => $request->b[$i] ?? null,
                'c'          => $request->c[$i] ?? null,
                'd'          => $request->d[$i] ?? null,
                'jawaban'    => $request->jawaban[$i] ?? null,
            ];

            foreach ($fileFields as $field) {
                if ($request->hasFile("{$field}.{$i}")) {
                    $file     = $request->file("{$field}.{$i}");
                    $filename = time() . "_{$field}_{$i}_" . $file->getClientOriginalName();
                    $file->move(public_path('uploads/ujians'), $filename);
                    $item[$field] = 'uploads/ujians/' . $filename;
                } else {
                    $item[$field] = $request->input("old_{$field}.{$i}") ?? null;
                }
            }
            $soal[] = $item;
        }

        $mapelIds    = $request->input('mapel_ids');
        $mapelLama   = $ujian->mapel_id;

        // Tentukan mapel utama: jika mapel lama masih dicentang → pakai itu,
        // jika tidak → pakai yang pertama dipilih
        $mapelPrimary = in_array($mapelLama, $mapelIds) ? $mapelLama : $mapelIds[0];

        // Update record utama
        $ujian->update([
            'mapel_id'    => $mapelPrimary,
            'judul'       => $request->judul,
            'status'      => $request->status,
            'jenis_ujian' => $request->jenis_ujian,
            'jam_mulai'   => $request->jam_mulai ?: null,
            'jam_selesai' => $request->jam_selesai ?: null,
            'soal'        => $soal,
        ]);

        // Duplikasi ke mapel tambahan
        $dupCount = 0;
        foreach ($mapelIds as $idx => $mapelId) {
            if ($mapelId == $mapelPrimary) continue; // sudah diupdate

            // Salin file gambar agar path unik per kelas
            $soalForThis = $soal;
            foreach ($soalForThis as &$item) {
                foreach ($fileFields as $field) {
                    if (!empty($item[$field])) {
                        $originalPath = public_path($item[$field]);
                        if (file_exists($originalPath)) {
                            $newName = time() . "_dup{$dupCount}_" . basename($item[$field]);
                            $newPath = public_path('uploads/ujians/' . $newName);
                            copy($originalPath, $newPath);
                            $item[$field] = 'uploads/ujians/' . $newName;
                        }
                    }
                }
            }
            unset($item);

            Ujian::create([
                'mapel_id'    => $mapelId,
                'judul'       => $request->judul,
                'status'      => $request->status,
                'jenis_ujian' => $request->jenis_ujian,
                'jam_mulai'   => $request->jam_mulai ?: null,
                'jam_selesai' => $request->jam_selesai ?: null,
                'soal'        => $soalForThis,
            ]);
            $dupCount++;
        }

        $msg = 'Ujian berhasil diupdate.';
        if ($dupCount > 0) {
            $msg .= " Diduplikasi ke {$dupCount} kelas baru.";
        }

        Alert::success('Success', $msg);
        return redirect()->route('ujian.index');
    }

    public function destroy(Ujian $ujian)
    {
        $ujian->delete();
        Alert::success('Success', 'Ujian berhasil dihapus');
        return back();
    }
}
