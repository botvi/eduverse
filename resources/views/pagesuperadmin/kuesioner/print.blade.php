<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Rekap Kuesioner & Peningkatan Minat Belajar — SMP Negeri 1 Benai</title>
  <style>
    @page { size: A4 portrait; margin: 15mm; }
    body { font-family: Arial, sans-serif; font-size: 11pt; color: #000; background: #fff; margin: 0; padding: 0; }
    .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 20px; position: relative; }
    .header h3 { margin: 0; font-size: 14pt; text-transform: uppercase; }
    .header h2 { margin: 2px 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
    .header p { margin: 0; font-size: 9pt; color: #333; }
    .doc-title { text-align: center; margin-bottom: 20px; }
    .doc-title h4 { margin: 0; font-size: 12pt; text-transform: uppercase; text-decoration: underline; }
    .doc-title p { margin: 3px 0 0; font-size: 9pt; color: #555; }
    
    .meta-table { width: 100%; margin-bottom: 15px; font-size: 10pt; }
    .meta-table td { padding: 3px 6px; }

    .summary-box { display: flex; gap: 15px; margin-bottom: 20px; }
    .summary-card { flex: 1; border: 1px solid #333; padding: 8px 12px; text-align: center; border-radius: 4px; }
    .summary-card .num { font-size: 14pt; font-weight: bold; }
    .summary-card .lbl { font-size: 8pt; color: #444; text-transform: uppercase; }

    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 9.5pt; }
    table.data-table th, table.data-table td { border: 1px solid #333; padding: 6px 8px; }
    table.data-table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
    table.data-table td.center { text-align: center; }
    
    .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 8.5pt; font-weight: bold; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-primary { background: #dbeafe; color: #1e40af; }
    .badge-secondary { background: #f3f4f6; color: #374151; }
    .badge-danger { background: #fee2e2; color: #991b1b; }

    .ttd-container { display: flex; justify-content: space-between; margin-top: 40px; page-break-inside: avoid; }
    .ttd-box { text-align: center; width: 220px; font-size: 10pt; }
    .ttd-space { height: 65px; }

    .btn-print-wrap { margin-bottom: 20px; text-align: right; }
    .btn-print { background: #2563eb; color: #fff; border: none; padding: 8px 16px; font-size: 10pt; font-weight: bold; border-radius: 4px; cursor: pointer; }
    @media print {
      .btn-print-wrap { display: none !important; }
    }
  </style>
</head>
<body>

  <div class="btn-print-wrap">
    <button onclick="window.print()" class="btn-print">🖨️ Cetak Laporan / Save PDF</button>
  </div>

  {{-- KOP SURAT --}}
  <div class="header">
    <h3>Pemerintah Kabupaten Kuantan Singingi</h3>
    <h2>Dinas Pendidikan, Kepemudaan, dan Olahraga</h2>
    <h2>SMP NEGERI 1 BENAI</h2>
    <p>Jl. Jenderal Sudirman No. 1 Benai, Kab. Kuantan Singingi, Riau | Kode Pos: 29566</p>
  </div>

  {{-- JUDUL DOKUMEN --}}
  <div class="doc-title">
    <h4>REKAPITULASI PENINGKATAN MINAT BELAJAR SISWA</h4>
    <p>Berdasarkan Hasil Kuesioner Pre-Test dan Post-Test Media Pembelajaran Digital Berbasis Web</p>
  </div>

  <table class="meta-table">
    <tr>
      <td width="15%"><strong>Sekolah</strong></td>
      <td width="35%">: SMP Negeri 1 Benai</td>
      <td width="15%"><strong>Kelas Filter</strong></td>
      <td width="35%">: {{ $kelasFilter ?? 'Semua Kelas' }}</td>
    </tr>
    <tr>
      <td><strong>Tanggal Cetak</strong></td>
      <td>: {{ date('d F Y') }}</td>
      <td><strong>Tahun Ajaran</strong></td>
      <td>: {{ $tahunFilter ?? 'Semua Tahun' }}</td>
    </tr>
  </table>

  {{-- RINGKASAN DATA --}}
  <div class="summary-box">
    <div class="summary-card">
      <div class="num">{{ $rataPreTest ? number_format($rataPreTest, 1) : '-' }}</div>
      <div class="lbl">Rata-rata Pre-Test</div>
    </div>
    <div class="summary-card">
      <div class="num">{{ $rataPostTest ? number_format($rataPostTest, 1) : '-' }}</div>
      <div class="lbl">Rata-rata Post-Test</div>
    </div>
    <div class="summary-card">
      <div class="num">{{ $analisisSiswa['rata_peningkatan'] !== null ? ($analisisSiswa['rata_peningkatan'] >= 0 ? '+' : '').$analisisSiswa['rata_peningkatan'] : '-' }}</div>
      <div class="lbl">Rata-rata Peningkatan Skor</div>
    </div>
    <div class="summary-card">
      <div class="num">{{ $analisisSiswa['persen_meningkat'] }}%</div>
      <div class="lbl">Siswa Mengalami Peningkatan</div>
    </div>
  </div>

  <h4>1. Tabel Perbandingan Peningkatan Minat Belajar Siswa (Pre-Test vs Post-Test)</h4>
  <table class="data-table">
    <thead>
      <tr>
        <th width="5%">No</th>
        <th>Nama Siswa</th>
        <th width="12%">Kelas</th>
        <th width="15%">Skor Pre-Test</th>
        <th width="15%">Skor Post-Test</th>
        <th width="18%">Peningkatan (Poin)</th>
        <th width="20%">Status Peningkatan</th>
      </tr>
    </thead>
    <tbody>
      @forelse($analisisSiswa['data'] as $idx => $item)
      <tr>
        <td class="center">{{ $idx + 1 }}</td>
        <td>{{ $item['user']->name ?? '-' }}</td>
        <td class="center">{{ $item['siswa']->kelas ?? '-' }}</td>
        <td class="center font-weight-bold">{{ $item['pre_skor'] !== null ? number_format($item['pre_skor'], 1) : '-' }}</td>
        <td class="center font-weight-bold">{{ $item['post_skor'] !== null ? number_format($item['post_skor'], 1) : '-' }}</td>
        <td class="center font-weight-bold">
          @if($item['peningkatan'] !== null)
            {{ $item['peningkatan'] >= 0 ? '+' : '' }}{{ number_format($item['peningkatan'], 1) }}
          @else
            -
          @endif
        </td>
        <td class="center">
          @if($item['status_peningkatan'] === 'Sangat Meningkat')
            <span class="badge badge-success">Sangat Meningkat</span>
          @elseif($item['status_peningkatan'] === 'Meningkat')
            <span class="badge badge-primary">Meningkat</span>
          @elseif($item['status_peningkatan'] === 'Tetap')
            <span class="badge badge-secondary">Tetap</span>
          @elseif($item['status_peningkatan'] === 'Menurun')
            <span class="badge badge-danger">Menurun</span>
          @else
            -
          @endif
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="center">Belum ada data perbandingan kuesioner siswa.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <h4>2. Tabel Log Pengisian Kuesioner Siswa</h4>
  <table class="data-table">
    <thead>
      <tr>
        <th width="5%">No</th>
        <th>Nama Siswa</th>
        <th width="12%">Kelas</th>
        <th width="15%">Jenis Kuesioner</th>
        <th width="12%">Skor Total</th>
        <th width="20%">Tanggal Pengisian</th>
      </tr>
    </thead>
    <tbody>
      @forelse($kuesioners as $i => $k)
      <tr>
        <td class="center">{{ $i + 1 }}</td>
        <td>{{ $k->user->name ?? '-' }}</td>
        <td class="center">{{ $k->user->siswa->kelas ?? '-' }}</td>
        <td class="center">
          <span class="badge {{ $k->jenis === 'pre_test' ? 'badge-primary' : 'badge-success' }}">
            {{ $k->jenis === 'pre_test' ? 'Pre-Test' : 'Post-Test' }}
          </span>
        </td>
        <td class="center font-weight-bold">{{ number_format($k->skor_total, 1) }}</td>
        <td class="center">{{ $k->created_at->format('d M Y H:i') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="center">Belum ada log pengisian kuesioner.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  {{-- TANDA TANGAN --}}
  <div class="ttd-container">
    <div class="ttd-box">
      <p>Mengetahui,<br>Guru Mata Pelajaran</p>
      <div class="ttd-space"></div>
      <p>_______________________<br>NIP. -</p>
    </div>
    <div class="ttd-box">
      <p>Benai, {{ date('d F Y') }}<br>Peneliti / Mahasiswa</p>
      <div class="ttd-space"></div>
      <p><strong>{{ Auth::user()->name ?? 'Peneliti' }}</strong><br>NIM. -</p>
    </div>
  </div>

</body>
</html>
