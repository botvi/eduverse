<?php

namespace App\Http\Controllers\superadmin;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('user')->latest();
        $kelasFilter = $request->query('kelas');
        $tahunFilter = $request->query('tahun_ajaran');

        if ($request->filled('kelas')) {
            $query->where('kelas', $kelasFilter);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $tahunFilter);
        }

        $siswas = $query->get();
        $kelasList = ['VII A', 'VII B', 'VII C', 'VIII A', 'VIII B', 'VIII C', 'IX A', 'IX B', 'IX C'];
        $tahunList = ['2023-2024', '2024-2025', '2025-2026', '2026-2027', '2027-2028'];

        return view('pagesuperadmin.data_siswa.index', compact('siswas', 'kelasList', 'kelasFilter', 'tahunList', 'tahunFilter'));
    }

    public function create()
    {
        return view('pagesuperadmin.data_siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn'          => 'required|unique:siswas',
            'nama_lengkap'  => 'required',
            'alamat'        => 'required',
            'kelas'         => 'required',
            'tahun_ajaran'  => 'required',
            'username'      => 'required|unique:users',
            'password'      => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->nama_lengkap,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        Siswa::create([
            'user_id'      => $user->id,
            'nisn'         => $request->nisn,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat'       => $request->alamat,
            'kelas'        => $request->kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        Alert::success('Success', 'Siswa berhasil ditambahkan');
        return redirect()->route('siswa.index');
    }

    public function edit(Siswa $siswa)
    {
        return view('pagesuperadmin.data_siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn'         => 'required|unique:siswas,nisn,' . $siswa->id,
            'nama_lengkap' => 'required',
            'alamat'       => 'required',
            'kelas'        => 'required',
            'tahun_ajaran' => 'required',
            'username'     => 'required|unique:users,username,' . $siswa->user_id,
        ]);

        $siswa->user->update([
            'name'     => $request->nama_lengkap,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $siswa->user->password,
        ]);

        $siswa->update([
            'nisn'         => $request->nisn,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat'       => $request->alamat,
            'kelas'        => $request->kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        Alert::success('Success', 'Siswa berhasil diupdate');
        return redirect()->route('siswa.index');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->user->delete(); // Automatically cascades to siswa if DB is set up right
        Alert::success('Success', 'Siswa berhasil dihapus');
        return back();
    }
}
