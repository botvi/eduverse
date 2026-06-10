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
                            <li class="breadcrumb-item"><a href="{{ route('nilai-akhir.index') }}">Nilai Akhir</a></li>
                            <li class="breadcrumb-item" aria-current="page">Input Nilai Absensi & Sikap</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Input Nilai Absensi & Sikap Siswa</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0">
                            <i class="ti ti-clipboard-list me-2 text-primary"></i>
                            Input Nilai Absensi & Sikap
                        </h5>
                        <a href="{{ route('nilai-akhir.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Kembali ke Rekap Nilai Akhir
                        </a>
                    </div>
                    <div class="card-body">

                        {{-- Alert info formula --}}
                        <div class="alert alert-info d-flex align-items-start gap-2 mb-4" style="font-size:0.9em;">
                            <i class="fas fa-info-circle mt-1"></i>
                            <div>
                                <strong>Formula Nilai Akhir (Persentase Bobot):</strong><br>
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    <span class="badge" style="background:#dc2626;font-size:0.9em;padding:6px 12px;">UAS – 30%</span>
                                    <span class="badge" style="background:#dc2626;font-size:0.9em;padding:6px 12px;">UTS – 30%</span>
                                    <span class="badge" style="background:#2563eb;font-size:0.9em;padding:6px 12px;">Quiz – 20%</span>
                                    <span class="badge" style="background:#059669;font-size:0.9em;padding:6px 12px;">Absensi – 10%</span>
                                    <span class="badge" style="background:#7c3aed;font-size:0.9em;padding:6px 12px;">Sikap – 10%</span>
                                </div>
                                <div class="mt-2 text-muted" style="font-size:0.88em;">
                                    Nilai Akhir = (Quiz × 0.20) + (Absensi × 0.10) + (Sikap × 0.10) + (UTS × 0.30) + (UAS × 0.30) &nbsp;|&nbsp; KKM: <strong>72</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Filter Kelas --}}
                        <form action="{{ route('nilai-akhir.input-nilai') }}" method="GET" class="mb-4 pb-3 border-bottom">
                            <div class="row align-items-end g-2">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Filter Kelas</label>
                                    <select name="kelas" class="form-control">
                                        <option value="">Semua Kelas</option>
                                        @foreach(\App\Models\Siswa::distinct()->orderBy('kelas')->pluck('kelas') as $k)
                                            <option value="{{ $k }}" {{ $kelasFilter == $k ? 'selected' : '' }}>{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary w-100">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('nilai-akhir.input-nilai') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-times me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>

                        @if($siswas->count() > 0)
                        {{-- Form input nilai --}}
                        <form action="{{ route('nilai-akhir.simpan-nilai') }}" method="POST" id="formNilai">
                            @csrf
                            <input type="hidden" name="kelas_filter" value="{{ $kelasFilter }}">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="text-muted mb-0" style="font-size:0.88em;">
                                    <i class="fas fa-users me-1"></i>
                                    Menampilkan <strong>{{ $siswas->count() }}</strong> siswa
                                    @if($kelasFilter) dari kelas <strong>{{ $kelasFilter }}</strong> @endif
                                </p>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-success btn-sm" id="btnIsiSemua">
                                        <i class="ti ti-pencil me-1"></i>Isi Nilai Default (100)
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save me-1"></i> Simpan Semua Nilai
                                    </button>
                                </div>
                            </div>

                            <div class="dt-responsive table-responsive">
                                <table id="inputNilaiTable" class="table table-striped table-bordered nowrap align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width:50px;">No</th>
                                            <th>NISN</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th class="text-center" style="min-width:160px;">
                                                Nilai Absensi
                                                <br><small class="fw-normal text-warning">(10% dari Nilai Akhir)</small>
                                            </th>
                                            <th class="text-center" style="min-width:160px;">
                                                Nilai Sikap
                                                <br><small class="fw-normal text-warning">(10% dari Nilai Akhir)</small>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($siswas as $i => $siswa)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $siswa->nisn }}</td>
                                            <td class="fw-semibold">{{ $siswa->nama_lengkap }}</td>
                                            <td><span class="badge bg-secondary">{{ $siswa->kelas }}</span></td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <input
                                                        type="number"
                                                        name="nilai[{{ $siswa->id }}][nilai_absensi]"
                                                        class="form-control text-center input-nilai"
                                                        min="0" max="100" step="1"
                                                        value="{{ $siswa->nilai_absensi ?? '' }}"
                                                        placeholder="0–100"
                                                        style="max-width:90px;"
                                                    >
                                                    @if($siswa->nilai_absensi !== null)
                                                        <span class="badge {{ $siswa->nilai_absensi >= 72 ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $siswa->nilai_absensi }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <input
                                                        type="number"
                                                        name="nilai[{{ $siswa->id }}][nilai_sikap]"
                                                        class="form-control text-center input-nilai"
                                                        min="0" max="100" step="1"
                                                        value="{{ $siswa->nilai_sikap ?? '' }}"
                                                        placeholder="0–100"
                                                        style="max-width:90px;"
                                                    >
                                                    @if($siswa->nilai_sikap !== null)
                                                        <span class="badge {{ $siswa->nilai_sikap >= 72 ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $siswa->nilai_sikap }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="fas fa-save me-2"></i>Simpan Semua Nilai
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                            <p>Tidak ada data siswa ditemukan.<br>Silakan pilih kelas terlebih dahulu.</p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        // Validasi input 0-100
        $('.input-nilai').on('input', function () {
            let val = parseInt($(this).val());
            if (val > 100) $(this).val(100);
            if (val < 0)   $(this).val(0);
        });

        // Tombol isi default 100
        $('#btnIsiSemua').on('click', function () {
            Swal.fire({
                title: 'Isi nilai default?',
                text: 'Kolom yang kosong akan diisi dengan nilai 100.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, isi!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
            }).then(result => {
                if (result.isConfirmed) {
                    $('.input-nilai').each(function () {
                        if (!$(this).val()) $(this).val(100);
                    });
                }
            });
        });

        // Konfirmasi simpan
        $('#formNilai').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Simpan nilai?',
                text: 'Nilai absensi dan sikap akan diperbarui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754',
            }).then(result => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection
