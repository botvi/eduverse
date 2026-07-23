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
                <li class="breadcrumb-item" aria-current="page">Tabel Data Siswa</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Tabel Data Siswa</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tabel Data Siswa</h5>
                <a href="{{ route('siswa.create') }}" class="btn btn-primary">Tambah Siswa</a>
            </div>
            <div class="card-body">
              <!-- Filter Kelas -->
              <form action="{{ route('siswa.index') }}" method="GET" class="mb-4 pb-3 border-bottom" id="filter-form">
                <div class="row align-items-end g-2">
                  <div class="col-md-3">
                    <label class="form-label fw-bold">Filter Kelas</label>
                    <select name="kelas" class="form-control" id="filter-kelas" onchange="document.getElementById('filter-form').submit()">
                      <option value="">Semua Kelas</option>
                      @foreach ($kelasList as $k)
                        <option value="{{ $k }}" {{ $kelasFilter == $k ? 'selected' : '' }}>
                          {{ $k }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label fw-bold">Filter Tahun Ajaran</label>
                    <select name="tahun_ajaran" class="form-control" id="filter-tahun" onchange="document.getElementById('filter-form').submit()">
                      <option value="">Semua Tahun</option>
                      @foreach ($tahunList as $t)
                        <option value="{{ $t }}" {{ ($tahunFilter ?? '') == $t ? 'selected' : '' }}>
                          {{ $t }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  @if($kelasFilter || ($tahunFilter ?? ''))
                  <div class="col-md-2">
                    <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary w-100">
                      <i class="fas fa-times"></i> Reset Filter
                    </a>
                  </div>
                  @endif
                </div>
              </form>

              {{-- Info filter aktif --}}
              @if ($kelasFilter || ($tahunFilter ?? ''))
                <div class="alert alert-info d-flex align-items-center gap-2 py-2 mb-3" style="font-size:0.88em;">
                  <i class="fas fa-filter"></i>
                  <div>
                    <strong>Filter aktif:</strong>
                    @if($kelasFilter) <span class="badge bg-primary ms-1">Kelas: {{ $kelasFilter }}</span> @endif
                    @if($tahunFilter ?? '') <span class="badge bg-info text-dark ms-1">TA: {{ $tahunFilter }}</span> @endif
                    &nbsp;–&nbsp; Menampilkan <strong>{{ $siswas->count() }}</strong> siswa
                  </div>
                </div>
              @endif

              <div class="dt-responsive table-responsive">
                <table id="simpletable" class="table table-striped table-bordered nowrap">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>NISN</th>
                      <th>Nama Lengkap</th>
                      <th>Username</th>
                      <th>Kelas</th>
                      <th>Tahun Ajaran</th>
                      <th>Alamat</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($siswas as $i => $item)
                    <tr>
                      <td>{{ $i+1 }}</td>
                      <td>{{ $item->nisn }}</td>
                      <td>{{ $item->nama_lengkap }}</td>
                      <td>{{ $item->user->username ?? '-' }}</td>
                      <td>{{ $item->kelas }}</td>
                      <td>
                        <span class="badge bg-secondary">{{ $item->tahun_ajaran ?? '-' }}</span>
                      </td>
                      <td>{{ $item->alamat }}</td>
                      <td>
                        <a href="{{ route('siswa.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" style="display:inline;" class="delete-form">
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
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
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
                    if (result.isConfirmed) {
                        form.submit();
                    }
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