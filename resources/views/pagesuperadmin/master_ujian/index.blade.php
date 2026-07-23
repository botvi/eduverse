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
                <li class="breadcrumb-item"><a href="javascript: void(0)">Master Ujian</a></li>
                <li class="breadcrumb-item" aria-current="page">Tabel Master Ujian</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Tabel Master Ujian</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tabel Master Ujian</h5>
                <a href="{{ route('ujian.create') }}" class="btn btn-primary">Tambah Ujian</a>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="simpletable" class="table table-striped table-bordered nowrap">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Judul Ujian</th>
                      <th>Jenis</th>
                      <th>Mapel</th>
                      <th>Kelas</th>
                      <th>Jumlah Soal</th>
                      <th>Jam Ujian</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($ujians as $i => $item)
                    <tr>
                      <td>{{ $i+1 }}</td>
                      <td>{{ $item->judul }}</td>
                      <td>
                        @if(($item->jenis_ujian ?? 'Lainnya') === 'UTS')
                          <span class="badge bg-warning text-dark">UTS</span>
                        @elseif(($item->jenis_ujian ?? 'Lainnya') === 'UAS')
                          <span class="badge bg-danger">UAS</span>
                        @else
                          <span class="badge bg-secondary">Lainnya</span>
                        @endif
                      </td>
                      <td>{{ $item->mapel->nama_mapel ?? '-' }}</td>
                      <td>{{ $item->mapel->kelas ?? '-' }}</td>
                      <td>{{ is_array($item->soal) ? count($item->soal) : 0 }} Soal</td>
                      <td>
                        @if($item->jam_mulai || $item->jam_selesai)
                          <small class="text-primary">
                            <i class="fas fa-clock"></i>
                            {{ $item->jam_mulai ? \Carbon\Carbon::createFromFormat('H:i:s', $item->jam_mulai)->format('H:i') : '–' }}
                            –
                            {{ $item->jam_selesai ? \Carbon\Carbon::createFromFormat('H:i:s', $item->jam_selesai)->format('H:i') : '–' }}
                          </small>
                        @else
                          <span class="text-muted small">–</span>
                        @endif
                      </td>
                      <td>
                          @if($item->status == 'dimulai')
                              <span class="badge bg-success">Dimulai</span>
                          @else
                              <span class="badge bg-secondary">Belum Dimulai</span>
                          @endif
                      </td>
                      <td>
                        <a href="{{ route('ujian.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('ujian.destroy', $item->id) }}" method="POST" style="display:inline;" class="delete-form">
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