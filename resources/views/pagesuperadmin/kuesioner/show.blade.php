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
              <li class="breadcrumb-item"><a href="{{ route('admin.kuesioner.index') }}">Rekap Kuesioner</a></li>
              <li class="breadcrumb-item" aria-current="page">Detail Jawaban</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title">
              <h2 class="mb-0">Detail Jawaban Kuesioner</h2>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              Detail Jawaban —
              <span class="badge {{ $kuesioner->jenis === 'pre_test' ? 'bg-primary' : 'bg-success' }}">
                {{ $kuesioner->jenis === 'pre_test' ? 'Pre-Test' : 'Post-Test' }}
              </span>
            </h5>
            <a href="{{ route('admin.kuesioner.index') }}" class="btn btn-sm btn-outline-secondary">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
          </div>
          <div class="card-body">

            {{-- Info Siswa --}}
            @php $siswa = $kuesioner->user->siswa ?? null; @endphp
            <div class="row mb-4">
              <div class="col-md-6">
                <table class="table table-borderless table-sm">
                  <tr><td class="text-muted fw-semibold" width="140">Nama Siswa</td><td>{{ $kuesioner->user->name ?? '-' }}</td></tr>
                  <tr><td class="text-muted fw-semibold">Kelas</td><td>{{ $siswa->kelas ?? '-' }}</td></tr>
                  <tr><td class="text-muted fw-semibold">Tahun Ajaran</td><td>{{ $siswa->tahun_ajaran ?? '-' }}</td></tr>
                  <tr><td class="text-muted fw-semibold">Tanggal Isi</td><td>{{ $kuesioner->created_at->format('d M Y H:i') }}</td></tr>
                </table>
              </div>
              <div class="col-md-6 text-center d-flex flex-column align-items-center justify-content-center">
                @php
                  $skor = $kuesioner->skor_total;
                  if ($skor >= 80)      { $kat = 'Sangat Tinggi'; $katColor = '#059669'; }
                  elseif ($skor >= 60)  { $kat = 'Tinggi'; $katColor = '#2563eb'; }
                  elseif ($skor >= 40)  { $kat = 'Sedang'; $katColor = '#d97706'; }
                  else                  { $kat = 'Rendah'; $katColor = '#dc2626'; }
                @endphp
                <div style="font-size:3rem; font-weight:900; color:{{ $katColor }}; font-family:monospace;">
                  {{ number_format($skor, 1) }}
                </div>
                <div class="text-muted fw-semibold">dari 100</div>
                <span class="badge mt-2" style="background:{{ $katColor }}; font-size:0.9em; padding:6px 16px;">
                  Minat Belajar: {{ $kat }}
                </span>
              </div>
            </div>

            <hr>

            {{-- Tabel Jawaban --}}
            @php
            $pertanyaan = \App\Models\Kuesioner::getPertanyaan($kuesioner->jenis);
            $labelSkala = ['','Sangat Tidak Setuju','Tidak Setuju','Netral','Setuju','Sangat Setuju'];
            @endphp
            <h6 class="fw-bold mb-3">Detail Jawaban per Pernyataan</h6>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="table-light">
                  <tr>
                    <th width="40">No</th>
                    <th>Pernyataan</th>
                    <th class="text-center" width="80">Nilai</th>
                    <th width="180">Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($pertanyaan as $no => $teks)
                  @php
                    $val = $kuesioner->jawaban[$no] ?? '-';
                    $colors = ['','bg-danger','bg-warning text-dark','bg-secondary','bg-info text-dark','bg-success'];
                  @endphp
                  <tr>
                    <td class="text-center fw-bold">{{ $no }}</td>
                    <td>{{ $teks }}</td>
                    <td class="text-center fw-bold fs-5">{{ $val }}</td>
                    <td>
                      @if(is_numeric($val))
                        <span class="badge {{ $colors[(int)$val] ?? '' }}">{{ $labelSkala[(int)$val] ?? '' }}</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot class="table-dark">
                  <tr>
                    <td colspan="2" class="text-end fw-bold">TOTAL SKOR</td>
                    <td class="text-center fw-bold">{{ array_sum(array_values($kuesioner->jawaban)) }}</td>
                    <td><span class="badge" style="background:{{ $katColor }}">{{ $kat }}</span></td>
                  </tr>
                </tfoot>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
