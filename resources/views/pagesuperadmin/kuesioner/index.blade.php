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
              <li class="breadcrumb-item" aria-current="page">Rekap Kuesioner</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title">
              <h2 class="mb-0">📋 Rekap Kuesioner Minat Belajar</h2>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm">
          <div class="card-body">
            <div class="text-primary fw-bold" style="font-size:2rem;">
              {{ \App\Models\Kuesioner::where('jenis','pre_test')->count() }}
            </div>
            <div class="text-muted small fw-semibold">Responden Pre-Test</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm">
          <div class="card-body">
            <div class="text-success fw-bold" style="font-size:2rem;">
              {{ \App\Models\Kuesioner::where('jenis','post_test')->count() }}
            </div>
            <div class="text-muted small fw-semibold">Responden Post-Test</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm">
          <div class="card-body">
            <div class="text-warning fw-bold" style="font-size:2rem;">
              {{ $rataPreTest ? number_format($rataPreTest, 1) : '–' }}
            </div>
            <div class="text-muted small fw-semibold">Rata-rata Skor Pre-Test</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center border-0 shadow-sm">
          <div class="card-body">
            @php
              $peningkatan = ($rataPreTest && $rataPostTest) ? round($rataPostTest - $rataPreTest, 1) : null;
            @endphp
            <div class="fw-bold" style="font-size:2rem; color:{{ $rataPostTest ? '#059669' : '#6b7280' }}">
              {{ $rataPostTest ? number_format($rataPostTest, 1) : '–' }}
            </div>
            <div class="text-muted small fw-semibold">
              Rata-rata Skor Post-Test
              @if($peningkatan !== null)
                <br><span class="badge {{ $peningkatan >= 0 ? 'bg-success' : 'bg-danger' }} mt-1">
                  {{ $peningkatan >= 0 ? '+' : '' }}{{ $peningkatan }} pts
                </span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Tabel Data Kuesioner</h5>
          </div>
          <div class="card-body">

            {{-- Filter --}}
            <form action="{{ route('admin.kuesioner.index') }}" method="GET" class="mb-4 pb-3 border-bottom">
              <div class="row g-2 align-items-end">
                <div class="col-md-3">
                  <label class="form-label fw-semibold">Jenis</label>
                  <select name="jenis" class="form-control">
                    <option value="">Semua Jenis</option>
                    <option value="pre_test"  {{ $jenisFilter == 'pre_test'  ? 'selected' : '' }}>Pre-Test</option>
                    <option value="post_test" {{ $jenisFilter == 'post_test' ? 'selected' : '' }}>Post-Test</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label fw-semibold">Kelas</label>
                  <select name="kelas" class="form-control">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                      <option value="{{ $k }}" {{ $kelasFilter == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label fw-semibold">Tahun Ajaran</label>
                  <select name="tahun_ajaran" class="form-control">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $t)
                      <option value="{{ $t }}" {{ $tahunFilter == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-secondary w-100">
                    <i class="fas fa-filter"></i> Filter
                  </button>
                </div>
                <div class="col-md-1">
                  <a href="{{ route('admin.kuesioner.index') }}" class="btn btn-outline-secondary w-100" title="Reset">
                    <i class="fas fa-times"></i>
                  </a>
                </div>
              </div>
            </form>

            @if($jenisFilter || $kelasFilter || $tahunFilter)
              <div class="alert alert-info d-flex align-items-center gap-2 py-2 mb-3" style="font-size:0.88em;">
                <i class="fas fa-filter"></i>
                <div>
                  <strong>Filter aktif:</strong>
                  @if($jenisFilter) <span class="badge bg-primary ms-1">{{ $jenisFilter == 'pre_test' ? 'Pre-Test' : 'Post-Test' }}</span> @endif
                  @if($kelasFilter) <span class="badge bg-info text-dark ms-1">Kelas: {{ $kelasFilter }}</span> @endif
                  @if($tahunFilter) <span class="badge bg-secondary ms-1">TA: {{ $tahunFilter }}</span> @endif
                  &nbsp;–&nbsp; Menampilkan <strong>{{ $kuesioners->count() }}</strong> data
                </div>
              </div>
            @endif

            <div class="dt-responsive table-responsive">
              <table id="kuesionertable" class="table table-striped table-bordered nowrap">
                <thead class="table-dark">
                  <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Jenis</th>
                    <th class="text-center">Skor</th>
                    <th class="text-center">Kategori</th>
                    <th>Tanggal Isi</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($kuesioners as $i => $item)
                  @php
                    $siswa = $item->user->siswa ?? null;
                    $skor  = $item->skor_total;
                    if ($skor >= 80)      { $kat = 'Sangat Tinggi'; $katColor = 'bg-success'; }
                    elseif ($skor >= 60)  { $kat = 'Tinggi'; $katColor = 'bg-primary'; }
                    elseif ($skor >= 40)  { $kat = 'Sedang'; $katColor = 'bg-warning text-dark'; }
                    else                  { $kat = 'Rendah'; $katColor = 'bg-danger'; }
                  @endphp
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->user->name ?? 'Unknown' }}</td>
                    <td>{{ $siswa->kelas ?? '-' }}</td>
                    <td>{{ $siswa->tahun_ajaran ?? '-' }}</td>
                    <td>
                      @if($item->jenis === 'pre_test')
                        <span class="badge bg-primary">Pre-Test</span>
                      @else
                        <span class="badge bg-success">Post-Test</span>
                      @endif
                    </td>
                    <td class="text-center fw-bold">{{ $skor ?? '–' }}</td>
                    <td class="text-center">
                      <span class="badge {{ $katColor }}">{{ $kat }}</span>
                    </td>
                    <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                    <td class="text-center">
                      <a href="{{ route('admin.kuesioner.show', $item->id) }}" class="btn btn-sm btn-info me-1">
                        <i class="fas fa-eye"></i>
                      </a>
                      <form action="{{ route('admin.kuesioner.destroy', $item->id) }}" method="POST" style="display:inline;" class="delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                      <i class="fas fa-clipboard me-2"></i>Belum ada data kuesioner.
                    </td>
                  </tr>
                  @endforelse
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
        title: 'Hapus data kuesioner?',
        text: 'Data ini akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, hapus!'
      }).then(result => {
        if (result.isConfirmed) form.submit();
      });
    });
  });
});
</script>
<script>
$(document).ready(function () {
  $('#kuesionertable').DataTable({ pageLength: 25, order: [[7, 'desc']] });
});
</script>
@endsection
