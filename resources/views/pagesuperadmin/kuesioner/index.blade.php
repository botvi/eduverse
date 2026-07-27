@extends('template-admin.layout')

@section('content')
<section class="pc-container">
  <div class="pc-content">
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-8">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="/dashboard-superadmin">Home</a></li>
              <li class="breadcrumb-item" aria-current="page">Rekap Kuesioner</li>
            </ul>
            <div class="page-header-title">
              <h2 class="mb-0">📋 Rekap Kuesioner & Peningkatan Minat Belajar</h2>
            </div>
          </div>
          <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <a href="{{ route('admin.kuesioner.print', request()->all()) }}" target="_blank" class="btn btn-primary shadow-sm">
              <i class="fas fa-print me-1"></i> Cetak Laporan / PDF
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Kartu Statistik Utama --}}
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
          <div class="card-body">
            <div class="text-primary fw-bold" style="font-size:2rem;">
              {{ \App\Models\Kuesioner::where('jenis','pre_test')->count() }}
            </div>
            <div class="text-muted small fw-semibold">Responden Pre-Test</div>
            <div class="text-primary mt-1 fw-bold">Rata-rata: {{ $rataPreTest ? number_format($rataPreTest, 1) : '-' }}</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
          <div class="card-body">
            <div class="text-success fw-bold" style="font-size:2rem;">
              {{ \App\Models\Kuesioner::where('jenis','post_test')->count() }}
            </div>
            <div class="text-muted small fw-semibold">Responden Post-Test</div>
            <div class="text-success mt-1 fw-bold">Rata-rata: {{ $rataPostTest ? number_format($rataPostTest, 1) : '-' }}</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
          <div class="card-body">
            @php
              $rataPen = $analisisSiswa['rata_peningkatan'];
            @endphp
            <div class="fw-bold {{ $rataPen >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:2rem;">
              {{ $rataPen !== null ? ($rataPen >= 0 ? '+' : '').$rataPen : '–' }}
            </div>
            <div class="text-muted small fw-semibold">Rata-rata Peningkatan Skor</div>
            <div class="text-secondary mt-1">Poin Minat Belajar</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
          <div class="card-body">
            <div class="text-info fw-bold" style="font-size:2rem;">
              {{ $analisisSiswa['persen_meningkat'] }}%
            </div>
            <div class="text-muted small fw-semibold">Siswa Mengalami Peningkatan</div>
            <div class="text-muted mt-1 style-italic" style="font-size:0.8em;">
              {{ $analisisSiswa['count_meningkat'] }} dari {{ $analisisSiswa['count_lengkap'] }} Siswa
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Main Content & Tabs --}}
    <div class="row">
      <div class="col-sm-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white pb-0 border-bottom-0">
            {{-- Filter Form --}}
            <form action="{{ route('admin.kuesioner.index') }}" method="GET" class="mb-3 pb-3 border-bottom">
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

            <ul class="nav nav-tabs card-header-tabs" id="kuesionerTabs" role="tablist">
              <li class="nav-item">
                <button class="nav-link active fw-bold" id="peningkatan-tab" data-bs-toggle="tab" data-bs-target="#peningkatan-pane" type="button" role="tab">
                  <i class="fas fa-chart-line me-1"></i> Perbandingan Peningkatan Siswa (Pre-Test vs Post-Test)
                </button>
              </li>
              <li class="nav-item">
                <button class="nav-link fw-bold" id="raw-tab" data-bs-toggle="tab" data-bs-target="#raw-pane" type="button" role="tab">
                  <i class="fas fa-table me-1"></i> Log Data Jawaban Kuesioner ({{ $kuesioners->count() }})
                </button>
              </li>
            </ul>
          </div>

          <div class="card-body">
            <div class="tab-content" id="kuesionerTabsContent">
              
              {{-- TAB 1: PERBANDINGAN PENINGKATAN SISWA --}}
              <div class="tab-pane fade show active" id="peningkatan-pane" role="tabpanel">
                <div class="table-responsive">
                  <table id="peningkatantable" class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                      <tr>
                        <th width="40">No</th>
                        <th>Nama Siswa</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Skor Pre-Test</th>
                        <th class="text-center">Skor Post-Test</th>
                        <th class="text-center">Peningkatan (Poin)</th>
                        <th class="text-center">Status Peningkatan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($analisisSiswa['data'] as $idx => $row)
                      <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="fw-semibold">{{ $row['user']->name ?? '-' }}</td>
                        <td class="text-center">{{ $row['siswa']->kelas ?? '-' }}</td>
                        <td class="text-center fw-bold text-primary">
                          {{ $row['pre_skor'] !== null ? number_format($row['pre_skor'], 1) : '–' }}
                        </td>
                        <td class="text-center fw-bold text-success">
                          {{ $row['post_skor'] !== null ? number_format($row['post_skor'], 1) : '–' }}
                        </td>
                        <td class="text-center">
                          @if($row['peningkatan'] !== null)
                            <span class="badge {{ $row['peningkatan'] >= 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                              {{ $row['peningkatan'] >= 0 ? '+' : '' }}{{ number_format($row['peningkatan'], 1) }}
                            </span>
                          @else
                            <span class="text-muted">–</span>
                          @endif
                        </td>
                        <td class="text-center fw-bold">
                          @if($row['status_peningkatan'] === 'Sangat Meningkat')
                            <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>Sangat Meningkat</span>
                          @elseif($row['status_peningkatan'] === 'Meningkat')
                            <span class="badge bg-primary"><i class="fas fa-arrow-up me-1"></i>Meningkat</span>
                          @elseif($row['status_peningkatan'] === 'Tetap')
                            <span class="badge bg-secondary"><i class="fas fa-minus me-1"></i>Tetap</span>
                          @elseif($row['status_peningkatan'] === 'Menurun')
                            <span class="badge bg-danger"><i class="fas fa-arrow-down me-1"></i>Menurun</span>
                          @else
                            <span class="text-muted">–</span>
                          @endif
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                          <i class="fas fa-clipboard-list me-2"></i>Belum ada data perbandingan kuesioner.
                        </td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

              {{-- TAB 2: RAW LOG KUESIONER --}}
              <div class="tab-pane fade" id="raw-pane" role="tabpanel">
                <div class="dt-responsive table-responsive">
                  <table id="kuesionertable" class="table table-striped table-bordered nowrap align-middle">
                    <thead class="table-dark">
                      <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Jenis</th>
                        <th class="text-center">Skor</th>
                        <th class="text-center">Kategori Minat</th>
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
                          <a href="{{ route('admin.kuesioner.show', $item->id) }}" class="btn btn-sm btn-info me-1" title="Detail Jawaban">
                            <i class="fas fa-eye"></i>
                          </a>
                          <form action="{{ route('admin.kuesioner.destroy', $item->id) }}" method="POST" style="display:inline;" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
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
  $('#peningkatantable').DataTable({ pageLength: 25 });
});
</script>
@endsection
