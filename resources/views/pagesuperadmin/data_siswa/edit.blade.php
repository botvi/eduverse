@extends('template-admin.layout')

@section('content')
    <section class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard-superadmin">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Data Siswa</a></li>
                                <li class="breadcrumb-item" aria-current="page">Form Edit Siswa</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Form Edit Siswa</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Form Edit Siswa</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">NISN</label>
                                        <input type="text" name="nisn" class="form-control"
                                            value="{{ $siswa->nisn }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" class="form-control"
                                            value="{{ $siswa->nama_lengkap }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Kelas</label>
                                        <select name="kelas" class="form-control" required>
                                            <optgroup label="Kelas VII">
                                                <option value="VII A" {{ $siswa->kelas == 'VII A' ? 'selected' : '' }}>VII
                                                    A
                                                </option>
                                                <option value="VII B" {{ $siswa->kelas == 'VII B' ? 'selected' : '' }}>VII
                                                    B
                                                </option>
                                                <option value="VII C" {{ $siswa->kelas == 'VII C' ? 'selected' : '' }}>VII
                                                    C
                                                </option>
                                            </optgroup>
                                            <optgroup label="Kelas VIII">
                                                <option value="VIII A" {{ $siswa->kelas == 'VIII A' ? 'selected' : '' }}>
                                                    VIII A
                                                </option>
                                                <option value="VIII B" {{ $siswa->kelas == 'VIII B' ? 'selected' : '' }}>
                                                    VIII B
                                                </option>
                                                <option value="VIII C" {{ $siswa->kelas == 'VIII C' ? 'selected' : '' }}>
                                                    VIII C
                                                </option>
                                            </optgroup>
                                            <optgroup label="Kelas IX">
                                                <option value="IX A" {{ $siswa->kelas == 'IX A' ? 'selected' : '' }}>IX A
                                                </option>
                                                <option value="IX B" {{ $siswa->kelas == 'IX B' ? 'selected' : '' }}>IX B
                                                </option>
                                                <option value="IX C" {{ $siswa->kelas == 'IX C' ? 'selected' : '' }}>IX C
                                                </option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" name="alamat" class="form-control"
                                            value="{{ $siswa->alamat }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                                        <select name="tahun_ajaran" class="form-control" required>
                                            <option value="">-- Pilih Tahun Ajaran --</option>
                                            @foreach(['2023-2024','2024-2025','2025-2026','2026-2027','2027-2028'] as $ta)
                                                <option value="{{ $ta }}" {{ ($siswa->tahun_ajaran ?? '') == $ta ? 'selected' : '' }}>
                                                    {{ $ta }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <h6>Akun Login</h6>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control"
                                            value="{{ $siswa->user->username ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">Password (Kosongkan jika tidak ingin diubah)</label>
                                        <input type="password" name="password" class="form-control" placeholder="Password">
                                    </div>
                                </div>

                                <div class="card-footer text-end mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                                    <a href="{{ route('siswa.index') }}" class="btn btn-light">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
