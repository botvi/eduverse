<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header justify-content-center">
            <a href="/dashboard-superadmin" class="b-brand text-primary" style="display:flex;align-items:center;gap:10px;">
                {{-- <img src="{{ asset('env/logo.png') }}" alt="SMP Negeri 1 Benai" style="height: 50px;object-fit:contain;"> --}}
                <span
                    style="font-family:'Orbitron',monospace;font-size:1.1em;font-weight:900;background:linear-gradient(90deg,#00c8ff,#a855f7);-webkit-background-clip:text;-webkit-text-fill-color:transparent;letter-spacing:2px;">SMP NEGERI 1 BENAI</span>
            </a>
        </div>
        @if (Auth::user()->role == 'superadmin')
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item">
                        <a href="/dashboard-superadmin" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Data SMP Negeri 1 Benai</label>
                        <i class="ti ti-dashboard"></i>
                    </li>


                    <li class="pc-item">
                        <a href="{{ route('mapel.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-bookmark"></i></span>
                            <span class="pc-mtext">Master Mapel</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('materi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-book"></i></span>
                            <span class="pc-mtext">Master Materi</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('quiz.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-list"></i></span>
                            <span class="pc-mtext">Master Quiz</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('ujian.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-file-text"></i></span>
                            <span class="pc-mtext">Master Ujian</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('siswa.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Data Siswa</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('nilai-quiz.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-chart-bar"></i></span>
                            <span class="pc-mtext">Nilai Quiz</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('nilai-ujian.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-chart-pie"></i></span>
                            <span class="pc-mtext">Nilai Ujian</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('nilai-akhir.input-nilai') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                            <span class="pc-mtext">Input Absensi & Sikap</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('nilai-akhir.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-trophy"></i></span>
                            <span class="pc-mtext">Nilai Akhir</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('admin.kuesioner.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-clipboard-check"></i></span>
                            <span class="pc-mtext">Rekap Kuesioner</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</nav>
