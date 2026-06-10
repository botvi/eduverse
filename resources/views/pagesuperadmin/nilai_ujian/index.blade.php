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
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Nilai Ujian</a></li>
                                <li class="breadcrumb-item" aria-current="page">Tabel Nilai Ujian</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Tabel Nilai Ujian</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Tabel Nilai Ujian</h5>
                            <div>
                                <a href="{{ route('nilai-ujian.print', request()->query()) }}" target="_blank"
                                    class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Cetak Laporan</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('nilai-ujian.index') }}" method="GET" class="mb-4 pb-3 border-bottom" id="filter-form">
                                <div class="row align-items-end g-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Kelas Siswa</label>
                                        <select name="kelas" class="form-control" id="filter-kelas">
                                            <option value="">Semua Kelas</option>
                                            @foreach ($kelasList as $k)
                                                <option value="{{ $k }}" {{ $kelasFilter == $k ? 'selected' : '' }}>
                                                    {{ $k }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Mata Pelajaran</label>
                                        <select name="mapel_id" class="form-control" id="filter-mapel">
                                            <option value="">Semua Mapel</option>
                                            @foreach ($mapels as $mapel)
                                                <option value="{{ $mapel->id }}"
                                                    data-kelas="{{ $mapel->kelas }}"
                                                    {{ $mapelFilter == $mapel->id ? 'selected' : '' }}>
                                                    {{ $mapel->nama_mapel }} - Kelas {{ $mapel->kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Waktu Pengerjaan</label>
                                        <input type="date" name="tanggal" class="form-control"
                                            value="{{ $tanggalFilter }}">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-secondary w-100">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="{{ route('nilai-ujian.index') }}" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-times"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>

                            {{-- Info filter aktif --}}
                            @if ($kelasFilter || $mapelFilter || $tanggalFilter)
                                <div class="alert alert-info d-flex align-items-center gap-2 py-2 mb-3" style="font-size:0.88em;">
                                    <i class="fas fa-filter"></i>
                                    <div>
                                        <strong>Filter aktif:</strong>
                                        @if($kelasFilter) <span class="badge bg-primary ms-1">Kelas: {{ $kelasFilter }}</span> @endif
                                        @if($mapelFilter)
                                            @php $mp = $mapels->firstWhere('id', $mapelFilter); @endphp
                                            <span class="badge bg-info text-dark ms-1">Mapel: {{ $mp->nama_mapel ?? '-' }}</span>
                                        @endif
                                        @if($tanggalFilter) <span class="badge bg-secondary ms-1">Tanggal: {{ \Carbon\Carbon::parse($tanggalFilter)->format('d M Y') }}</span> @endif
                                        &nbsp;–&nbsp; Menampilkan <strong>{{ $nilais->count() }}</strong> data
                                    </div>
                                </div>
                            @endif

                            <div class="dt-responsive table-responsive">
                                <table id="simpletable" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Siswa</th>
                                            <th>Kelas Siswa</th>
                                            <th>Judul Ujian</th>
                                            <th>Mapel</th>
                                            <th>Nilai</th>
                                            <th>Keterangan</th>
                                            <th>Waktu Dikerjakan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($nilais as $i => $item)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $item->user->name ?? 'Unknown' }}</td>
                                                <td>{{ $item->user->siswa->kelas ?? '-' }}</td>
                                                <td>{{ $item->ujian->judul ?? '-' }}</td>
                                                <td>{{ $item->ujian->mapel->nama_mapel ?? '-' }}</td>
                                                <td>
                                                    @if ($item->nilai_ujian < 72)
                                                        <span class="badge bg-danger">Tidak Lulus
                                                            ({{ $item->nilai_ujian }})
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">Lulus
                                                            ({{ $item->nilai_ujian }})</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->is_remedial)
                                                        @if ($item->nilai_ujian >= 72)
                                                            <span class="badge" style="background-color:#f39c12;">&#10003;
                                                                Lulus Setelah Remedial</span>
                                                        @else
                                                            <span class="badge bg-danger">&#8635; Remedial &ndash; Belum
                                                                Lulus</span>
                                                        @endif
                                                    @else
                                                        @if ($item->nilai_ujian >= 72)
                                                            <span class="badge bg-success">&#10003; Lulus</span>
                                                        @else
                                                            <span class="badge bg-secondary">&#9888; Perlu Remedial</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    <form action="{{ route('nilai-ujian.destroy', $item->id) }}"
                                                        method="POST" style="display:inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter mapel berdasarkan kelas yang dipilih
            const kelasSelect = document.getElementById('filter-kelas');
            const mapelSelect = document.getElementById('filter-mapel');
            const allMapelOptions = Array.from(mapelSelect.querySelectorAll('option[data-kelas]'));

            function filterMapel(selectedKelas) {
                const currentVal = mapelSelect.value;
                allMapelOptions.forEach(opt => {
                    if (!selectedKelas || opt.dataset.kelas.startsWith(selectedKelas.split(' ')[0])) {
                        opt.style.display = '';
                    } else {
                        opt.style.display = 'none';
                    }
                });
                // Reset mapel jika opsi yang dipilih tidak tersedia
                const visible = allMapelOptions.find(o => o.value === currentVal && o.style.display !== 'none');
                if (!visible && selectedKelas) {
                    mapelSelect.value = '';
                }
            }

            kelasSelect.addEventListener('change', function () {
                filterMapel(this.value);
            });

            // Jalankan saat load untuk menjaga state filter
            filterMapel(kelasSelect.value);

            // Konfirmasi hapus
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#simpletable').DataTable();
        });
    </script>
@endsection
