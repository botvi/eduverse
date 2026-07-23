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
                                <li class="breadcrumb-item" aria-current="page">Form Edit Ujian</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Form Edit Ujian</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <form action="{{ route('ujian.update', $ujian->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Info Ujian</h5>
                                <small class="text-muted">
                                    Mapel aktif sudah tercentang. Centang mapel lain untuk <strong>menduplikasi</strong>
                                    ujian ke kelas tersebut.
                                </small>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label">Judul Ujian</label>
                                    <input type="text" name="judul" class="form-control" value="{{ $ujian->judul }}"
                                        required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Jenis Ujian</label>
                                    <select name="jenis_ujian" class="form-control" required>
                                        @foreach(['Lainnya', 'UTS', 'UAS'] as $jenis)
                                            <option value="{{ $jenis }}" {{ ($ujian->jenis_ujian ?? 'Lainnya') == $jenis ? 'selected' : '' }}>
                                                {{ $jenis === 'UTS' ? 'UTS (Ujian Tengah Semester)' : ($jenis === 'UAS' ? 'UAS (Ujian Akhir Semester)' : 'Lainnya') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Status Ujian</label>
                                    <select name="status" class="form-control" required>
                                        <option value="belum dimulai"
                                            {{ $ujian->status == 'belum dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                                        <option value="dimulai" {{ $ujian->status == 'dimulai' ? 'selected' : '' }}>Dimulai
                                        </option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label class="form-label fw-semibold">Jam Mulai <small class="text-muted">(opsional)</small></label>
                                        <input type="time" name="jam_mulai" class="form-control" value="{{ $ujian->jam_mulai ?? '' }}">
                                        <small class="text-muted">Siswa tidak bisa mengakses ujian sebelum jam ini.</small>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label class="form-label fw-semibold">Jam Selesai <small class="text-muted">(opsional)</small></label>
                                        <input type="time" name="jam_selesai" class="form-control" value="{{ $ujian->jam_selesai ?? '' }}">
                                        <small class="text-muted">Siswa tidak bisa mengakses ujian setelah jam ini.</small>
                                    </div>
                                </div>

                                {{-- ===================== PILIH MAPEL MULTI ===================== --}}
                                <div class="form-group mt-3">
                                    <label class="form-label fw-semibold">
                                        Mapel / Kelas <span class="text-danger">*</span>
                                    </label>
                                    <p class="text-muted small mb-2">
                                        @if ($ujian->mapel)
                                            <span class="badge bg-primary">{{ $ujian->mapel->nama_mapel }} - Kelas
                                                {{ $ujian->mapel->kelas }}</span>
                                            adalah mapel yang sedang diedit.
                                        @endif
                                        Centang kelas lain dalam materi yang sama untuk <strong>menduplikasi</strong> ujian
                                        ini ke kelas tersebut sekaligus.
                                    </p>

                                    {{-- Tombol bantu --}}
                                    <div class="mb-2 d-flex gap-2 flex-wrap align-items-center">
                                        <input type="text" id="searchMapel" class="form-control" style="max-width:220px;"
                                            placeholder="🔍 Cari nama mapel...">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="pilihSemuaMapel">✔
                                            Pilih Semua</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            id="hapusSemuaMapel">✖ Hapus Semua</button>
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                            onclick="pilihTingkat('VII')">Kelas VII</button>
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                            onclick="pilihTingkat('VIII')">Kelas VIII</button>
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                            onclick="pilihTingkat('IX')">Kelas IX</button>
                                    </div>

                                    <div class="border rounded p-3" style="max-height:280px; overflow-y:auto;"
                                        id="mapelCheckboxList">
                                        @php
                                            $mapelGrouped = $mapels->groupBy('nama_mapel');
                                            $selectedIds = old('mapel_ids', [$ujian->mapel_id]);
                                        @endphp

                                        @foreach ($mapelGrouped as $namaMapel => $items)
                                            <div class="mapel-group mb-2">
                                                <strong class="text-primary small">{{ $namaMapel }}</strong>
                                                <div class="ps-2 mt-1 d-flex flex-wrap gap-3">
                                                    @foreach ($items as $mapel)
                                                        <div class="form-check mapel-item"
                                                            data-nama="{{ strtolower($namaMapel) }}"
                                                            data-kelas="{{ $mapel->kelas }}">
                                                            <input class="form-check-input mapel-check" type="checkbox"
                                                                name="mapel_ids[]" value="{{ $mapel->id }}"
                                                                id="mapel_{{ $mapel->id }}"
                                                                {{ in_array($mapel->id, $selectedIds) ? 'checked' : '' }}>
                                                            <label class="form-check-label small"
                                                                for="mapel_{{ $mapel->id }}">
                                                                Kelas {{ $mapel->kelas }}
                                                                @if ($mapel->id == $ujian->mapel_id)
                                                                    <span class="badge bg-primary ms-1"
                                                                        style="font-size:10px;">Aktif</span>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <hr class="my-1">
                                        @endforeach

                                        @if ($mapels->isEmpty())
                                            <p class="text-muted">Belum ada mata pelajaran. <a
                                                    href="{{ route('mapel.create') }}">Tambah mapel dulu</a>.</p>
                                        @endif
                                    </div>

                                    @error('mapel_ids')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div id="soal-container">
                            @php $soals = is_array($ujian->soal) ? $ujian->soal : []; @endphp
                            @if (count($soals) > 0)
                                @foreach ($soals as $index => $soal)
                                    <div class="card mb-3 soal-item">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="soal-title">Soal {{ $index + 1 }}</h5>
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-soal"
                                                style="display: {{ count($soals) > 1 ? 'block' : 'none' }};">Hapus
                                                Soal</button>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Gambar Pertanyaan (Opsional)</label>
                                                <input type="hidden" name="old_gambar_pertanyaan[]"
                                                    value="{{ $soal['gambar_pertanyaan'] ?? '' }}">
                                                @if (!empty($soal['gambar_pertanyaan']))
                                                    <div class="mb-2"><img src="{{ asset($soal['gambar_pertanyaan']) }}"
                                                            alt="Gambar" width="150"></div>
                                                @endif
                                                <input type="file" name="gambar_pertanyaan[]" class="form-control"
                                                    accept="image/*">
                                                <label class="form-label mt-2">Pertanyaan</label>
                                                <textarea name="pertanyaan[]" class="form-control" rows="3">{{ $soal['pertanyaan'] ?? '' }}</textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group mb-3">
                                                    <label class="form-label">Gambar Pilihan A (Opsional)</label>
                                                    <input type="hidden" name="old_gambar_a[]"
                                                        value="{{ $soal['gambar_a'] ?? '' }}">
                                                    @if (!empty($soal['gambar_a']))
                                                        <div class="mb-1"><img src="{{ asset($soal['gambar_a']) }}"
                                                                alt="Gambar A" width="100"></div>
                                                    @endif
                                                    <input type="file" name="gambar_a[]" class="form-control mb-1"
                                                        accept="image/*">
                                                    <label class="form-label mt-1">Pilihan A</label>
                                                    <input type="text" name="a[]" class="form-control"
                                                        value="{{ $soal['a'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label class="form-label">Gambar Pilihan B (Opsional)</label>
                                                    <input type="hidden" name="old_gambar_b[]"
                                                        value="{{ $soal['gambar_b'] ?? '' }}">
                                                    @if (!empty($soal['gambar_b']))
                                                        <div class="mb-1"><img src="{{ asset($soal['gambar_b']) }}"
                                                                alt="Gambar B" width="100"></div>
                                                    @endif
                                                    <input type="file" name="gambar_b[]" class="form-control mb-1"
                                                        accept="image/*">
                                                    <label class="form-label mt-1">Pilihan B</label>
                                                    <input type="text" name="b[]" class="form-control"
                                                        value="{{ $soal['b'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label class="form-label">Gambar Pilihan C (Opsional)</label>
                                                    <input type="hidden" name="old_gambar_c[]"
                                                        value="{{ $soal['gambar_c'] ?? '' }}">
                                                    @if (!empty($soal['gambar_c']))
                                                        <div class="mb-1"><img src="{{ asset($soal['gambar_c']) }}"
                                                                alt="Gambar C" width="100"></div>
                                                    @endif
                                                    <input type="file" name="gambar_c[]" class="form-control mb-1"
                                                        accept="image/*">
                                                    <label class="form-label mt-1">Pilihan C</label>
                                                    <input type="text" name="c[]" class="form-control"
                                                        value="{{ $soal['c'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label class="form-label">Gambar Pilihan D (Opsional)</label>
                                                    <input type="hidden" name="old_gambar_d[]"
                                                        value="{{ $soal['gambar_d'] ?? '' }}">
                                                    @if (!empty($soal['gambar_d']))
                                                        <div class="mb-1"><img src="{{ asset($soal['gambar_d']) }}"
                                                                alt="Gambar D" width="100"></div>
                                                    @endif
                                                    <input type="file" name="gambar_d[]" class="form-control mb-1"
                                                        accept="image/*">
                                                    <label class="form-label mt-1">Pilihan D</label>
                                                    <input type="text" name="d[]" class="form-control"
                                                        value="{{ $soal['d'] ?? '' }}">
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label class="form-label">Kunci Jawaban</label>
                                                    <select name="jawaban[]" class="form-control" required>
                                                        <option value="a"
                                                            {{ isset($soal['jawaban']) && $soal['jawaban'] == 'a' ? 'selected' : '' }}>
                                                            A</option>
                                                        <option value="b"
                                                            {{ isset($soal['jawaban']) && $soal['jawaban'] == 'b' ? 'selected' : '' }}>
                                                            B</option>
                                                        <option value="c"
                                                            {{ isset($soal['jawaban']) && $soal['jawaban'] == 'c' ? 'selected' : '' }}>
                                                            C</option>
                                                        <option value="d"
                                                            {{ isset($soal['jawaban']) && $soal['jawaban'] == 'd' ? 'selected' : '' }}>
                                                            D</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="card mb-3 soal-item">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="soal-title">Soal 1</h5>
                                        <button type="button" class="btn btn-sm btn-danger btn-remove-soal"
                                            style="display: none;">Hapus Soal</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Gambar Pertanyaan (Opsional)</label>
                                            <input type="file" name="gambar_pertanyaan[]" class="form-control"
                                                accept="image/*">
                                            <label class="form-label mt-2">Pertanyaan</label>
                                            <textarea name="pertanyaan[]" class="form-control" rows="3"></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group mb-3">
                                                <label class="form-label">Gambar Pilihan A (Opsional)</label>
                                                <input type="file" name="gambar_a[]" class="form-control mb-1"
                                                    accept="image/*">
                                                <label class="form-label mt-1">Pilihan A</label>
                                                <input type="text" name="a[]" class="form-control">
                                            </div>
                                            <div class="col-md-6 form-group mb-3">
                                                <label class="form-label">Gambar Pilihan B (Opsional)</label>
                                                <input type="file" name="gambar_b[]" class="form-control mb-1"
                                                    accept="image/*">
                                                <label class="form-label mt-1">Pilihan B</label>
                                                <input type="text" name="b[]" class="form-control">
                                            </div>
                                            <div class="col-md-6 form-group mb-3">
                                                <label class="form-label">Gambar Pilihan C (Opsional)</label>
                                                <input type="file" name="gambar_c[]" class="form-control mb-1"
                                                    accept="image/*">
                                                <label class="form-label mt-1">Pilihan C</label>
                                                <input type="text" name="c[]" class="form-control">
                                            </div>
                                            <div class="col-md-6 form-group mb-3">
                                                <label class="form-label">Gambar Pilihan D (Opsional)</label>
                                                <input type="file" name="gambar_d[]" class="form-control mb-1"
                                                    accept="image/*">
                                                <label class="form-label mt-1">Pilihan D</label>
                                                <input type="text" name="d[]" class="form-control">
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
                            @endif
                        </div>

                        <div class="mb-4">
                            <button type="button" class="btn btn-success" id="btn-add-soal">
                                <i class="ti ti-plus"></i> Tambah Soal Lain
                            </button>
                        </div>

                        <div class="card">
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary me-2">Update &amp; Duplikasi</button>
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
            const btnAdd = document.getElementById('btn-add-soal');

            function updateSoalUI() {
                const items = container.querySelectorAll('.soal-item');
                items.forEach((item, index) => {
                    item.querySelector('.soal-title').textContent = 'Soal ' + (index + 1);
                    item.querySelector('.btn-remove-soal').style.display = items.length > 1 ? 'block' :
                        'none';
                });
            }

            btnAdd.addEventListener('click', function() {
                const firstItem = container.querySelectorAll('.soal-item')[0];
                const newItem = firstItem.cloneNode(true);
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
