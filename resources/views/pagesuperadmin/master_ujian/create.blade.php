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
                <li class="breadcrumb-item"><a href="{{ route('ujian.index') }}">Master Ujian</a></li>
                <li class="breadcrumb-item" aria-current="page">Form Tambah Ujian</li>
              </ul>
            </div>
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Form Tambah Ujian</h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-sm-12">
          <form action="{{ route('ujian.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ===================== INFO UJIAN ===================== --}}
            <div class="card mb-3">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Info Ujian</h5>
                <small class="text-muted">Centang beberapa kelas sekaligus — soal yang sama akan disalin ke semua kelas</small>
              </div>
              <div class="card-body">

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">Judul Ujian</label>
                    <input type="text" name="judul" class="form-control" placeholder="Masukkan judul ujian..." value="{{ old('judul') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">Jenis Ujian</label>
                    <select name="jenis_ujian" class="form-control" required>
                        <option value="Lainnya">Lainnya</option>
                        <option value="UTS">UTS (Ujian Tengah Semester)</option>
                        <option value="UAS">UAS (Ujian Akhir Semester)</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-semibold">Status Ujian</label>
                    <select name="status" class="form-control" required>
                        <option value="belum dimulai">Belum Dimulai</option>
                        <option value="dimulai">Dimulai</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label fw-semibold">Jam Mulai <small class="text-muted">(opsional)</small></label>
                        <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}">
                        <small class="text-muted">Siswa tidak bisa mengakses ujian sebelum jam ini.</small>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label fw-semibold">Jam Selesai <small class="text-muted">(opsional)</small></label>
                        <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}">
                        <small class="text-muted">Siswa tidak bisa mengakses ujian setelah jam ini.</small>
                    </div>
                </div>

                {{-- ===================== PILIH MAPEL MULTI ===================== --}}
                <div class="form-group mb-0">
                  <label class="form-label fw-semibold">
                    Mapel / Kelas <span class="text-danger">*</span>
                  </label>
                  <p class="text-muted small mb-2">
                    Centang semua kelas yang akan mengikuti ujian dengan soal yang sama ini sekaligus.
                  </p>

                  {{-- Tombol bantu --}}
                  <div class="mb-2 d-flex gap-2 flex-wrap align-items-center">
                    <input type="text" id="searchMapel" class="form-control" style="max-width:220px;" placeholder="🔍 Cari nama mapel...">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="pilihSemuaMapel">✔ Pilih Semua</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="hapusSemuaMapel">✖ Hapus Semua</button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="pilihTingkat('VII')">Kelas VII</button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="pilihTingkat('VIII')">Kelas VIII</button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="pilihTingkat('IX')">Kelas IX</button>
                  </div>

                  <div class="border rounded p-3" style="max-height:280px; overflow-y:auto;" id="mapelCheckboxList">
                    @php
                      $mapelGrouped = $mapels->groupBy('nama_mapel');
                    @endphp

                    @foreach ($mapelGrouped as $namaMapel => $items)
                      <div class="mapel-group mb-2">
                        <strong class="text-primary small">{{ $namaMapel }}</strong>
                        <div class="ps-2 mt-1 d-flex flex-wrap gap-3">
                          @foreach ($items as $mapel)
                            <div class="form-check mapel-item" data-nama="{{ strtolower($namaMapel) }}" data-kelas="{{ $mapel->kelas }}">
                              <input class="form-check-input mapel-check" type="checkbox"
                                name="mapel_ids[]"
                                value="{{ $mapel->id }}"
                                id="mapel_{{ $mapel->id }}"
                                {{ in_array($mapel->id, old('mapel_ids', [])) ? 'checked' : '' }}>
                              <label class="form-check-label small" for="mapel_{{ $mapel->id }}">
                                Kelas {{ $mapel->kelas }}
                              </label>
                            </div>
                          @endforeach
                        </div>
                      </div>
                      <hr class="my-1">
                    @endforeach

                    @if($mapels->isEmpty())
                      <p class="text-muted">Belum ada mata pelajaran. <a href="{{ route('mapel.create') }}">Tambah mapel dulu</a>.</p>
                    @endif
                  </div>

                  @error('mapel_ids')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                  @enderror
                </div>

              </div>
            </div>

            {{-- ===================== SOAL ===================== --}}
            <div id="soal-container">
                <!-- Soal Item 1 -->
                <div class="card mb-3 soal-item">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="soal-title">Soal 1</h5>
                    <button type="button" class="btn btn-sm btn-danger btn-remove-soal" style="display: none;">Hapus Soal</button>
                  </div>
                  <div class="card-body">
                    <div class="form-group mb-3">
                      <label class="form-label">Gambar Pertanyaan (Opsional)</label>
                      <input type="file" name="gambar_pertanyaan[]" class="form-control" accept="image/*">
                      <label class="form-label mt-2">Pertanyaan</label>
                      <textarea name="pertanyaan[]" class="form-control" rows="3" placeholder="Masukkan pertanyaan..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                          <label class="form-label">Gambar Pilihan A (Opsional)</label>
                          <input type="file" name="gambar_a[]" class="form-control mb-1" accept="image/*">
                          <label class="form-label mt-1">Pilihan A</label>
                          <input type="text" name="a[]" class="form-control" placeholder="Teks pilihan A">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                          <label class="form-label">Gambar Pilihan B (Opsional)</label>
                          <input type="file" name="gambar_b[]" class="form-control mb-1" accept="image/*">
                          <label class="form-label mt-1">Pilihan B</label>
                          <input type="text" name="b[]" class="form-control" placeholder="Teks pilihan B">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                          <label class="form-label">Gambar Pilihan C (Opsional)</label>
                          <input type="file" name="gambar_c[]" class="form-control mb-1" accept="image/*">
                          <label class="form-label mt-1">Pilihan C</label>
                          <input type="text" name="c[]" class="form-control" placeholder="Teks pilihan C">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                          <label class="form-label">Gambar Pilihan D (Opsional)</label>
                          <input type="file" name="gambar_d[]" class="form-control mb-1" accept="image/*">
                          <label class="form-label mt-1">Pilihan D</label>
                          <input type="text" name="d[]" class="form-control" placeholder="Teks pilihan D">
                        </div>
                        <div class="col-md-12 form-group">
                          <label class="form-label">Kunci Jawaban</label>
                          <select name="jawaban[]" class="form-control" required>
                              <option value="a">A</option>
                              <option value="b">B</option>
                              <option value="c">C</option>
                              <option value="d">D</option>
                          </select>
                        </div>
                    </div>
                  </div>
                </div>
            </div>

            <div class="mb-4">
                <button type="button" class="btn btn-success" id="btn-add-soal">
                    <i class="ti ti-plus"></i> Tambah Soal Lain
                </button>
            </div>

            <div class="card">
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary me-2">Simpan Ujian</button>
                    <a href="{{ route('ujian.index') }}" class="btn btn-light">Kembali</a>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== Tambah / hapus soal =====
    const container = document.getElementById('soal-container');
    const btnAdd    = document.getElementById('btn-add-soal');

    function updateSoalUI() {
        const items = container.querySelectorAll('.soal-item');
        items.forEach((item, index) => {
            item.querySelector('.soal-title').textContent = 'Soal ' + (index + 1);
            const btnRemove = item.querySelector('.btn-remove-soal');
            btnRemove.style.display = items.length > 1 ? 'block' : 'none';
        });
    }

    btnAdd.addEventListener('click', function() {
        const firstItem = container.querySelectorAll('.soal-item')[0];
        const newItem   = firstItem.cloneNode(true);
        newItem.querySelectorAll('input, textarea').forEach(el => el.value = '');
        newItem.querySelectorAll('select').forEach(el => el.selectedIndex = 0);
        container.appendChild(newItem);
        updateSoalUI();
    });

    container.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-remove-soal');
        if (btn) {
            btn.closest('.soal-item').remove();
            updateSoalUI();
        }
    });

    // ===== Pilih semua / hapus semua mapel =====
    document.getElementById('pilihSemuaMapel').addEventListener('click', function() {
        document.querySelectorAll('.mapel-check').forEach(cb => {
            if (cb.closest('.mapel-item').style.display !== 'none') cb.checked = true;
        });
    });
    document.getElementById('hapusSemuaMapel').addEventListener('click', function() {
        document.querySelectorAll('.mapel-check').forEach(cb => cb.checked = false);
    });

    // ===== Filter tingkat kelas =====
    window.pilihTingkat = function(tingkat) {
        document.querySelectorAll('.mapel-item').forEach(item => {
            const kelas = item.dataset.kelas || '';
            if (kelas.startsWith(tingkat)) {
                const cb = item.querySelector('.mapel-check');
                cb.checked = !cb.checked;
            }
        });
    };

    // ===== Search mapel =====
    document.getElementById('searchMapel').addEventListener('input', function() {
        const keyword = this.value.toLowerCase().trim();
        document.querySelectorAll('.mapel-group').forEach(group => {
            const nama = group.querySelector('strong').textContent.toLowerCase();
            group.style.display = nama.includes(keyword) ? '' : 'none';
        });
    });
});
</script>
@endsection