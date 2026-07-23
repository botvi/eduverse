<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMP Negeri 1 Benai — Kuesioner {{ $labelJenis }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;700;900&family=Nunito:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
            --nb: #0ea5e9;
            --np: #8b5cf6;
            --ng: #10b981;
            --ny: #eab308;
            --nr: #f43f5e;
            --tx: #0f172a;
            --mu: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            color: var(--tx);
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(139, 92, 246, .08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139, 92, 246, .08) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes gridMove { to { transform: translateY(50px); } }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: .12;
            pointer-events: none;
        }
        .o1 { width: 400px; height: 400px; background: var(--nb); top: -120px; left: -100px; }
        .o2 { width: 350px; height: 350px; background: var(--np); bottom: -100px; right: -80px; }

        .topbar {
            position: sticky; top: 0;
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 24px;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            z-index: 100;
        }

        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 1.05em; font-weight: 900;
            background: linear-gradient(90deg, var(--nb), var(--np));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 2px;
        }

        .btn-sm {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--mu);
            padding: 7px 14px; border-radius: 8px;
            font-family: 'Nunito'; font-size: .85em; font-weight: 700;
            cursor: pointer; text-decoration: none;
            transition: .2s;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .btn-sm:hover { border-color: var(--nb); color: var(--nb); }

        .wrap {
            max-width: 780px;
            margin: 0 auto;
            padding: 36px 20px;
            position: relative; z-index: 1;
        }

        .page-header { text-align: center; margin-bottom: 32px; }
        .page-header h1 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(1.4rem, 3.5vw, 2rem);
            font-weight: 900;
            background: linear-gradient(135deg, var(--nb), var(--np));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        .page-header p { color: var(--mu); }

        .badge-jenis {
            display: inline-block;
            background: linear-gradient(135deg, var(--np), var(--nb));
            color: #fff;
            padding: 4px 18px; border-radius: 20px;
            font-size: .8em; font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .info-box {
            background: rgba(14, 165, 233, .07);
            border: 1px solid rgba(14, 165, 233, .25);
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 28px;
            font-size: .9em;
            color: var(--mu);
        }

        .info-box strong { color: var(--nb); }

        .done-box {
            background: rgba(16, 185, 129, .08);
            border: 2px solid var(--ng);
            border-radius: 16px;
            padding: 36px;
            text-align: center;
        }

        .done-box i { font-size: 3rem; color: var(--ng); display: block; margin-bottom: 16px; }
        .done-box h2 { font-family: 'Orbitron', monospace; font-size: 1.4em; color: var(--ng); margin-bottom: 8px; }
        .done-box p { color: var(--mu); }

        .form-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.05);
        }

        .form-card-header {
            background: linear-gradient(135deg, var(--np), var(--nb));
            padding: 20px 28px;
            color: #fff;
        }

        .form-card-header h2 {
            font-family: 'Orbitron', monospace;
            font-size: 1.1em;
            font-weight: 900;
            margin-bottom: 4px;
        }

        .form-card-header p { font-size: .85em; opacity: .85; }

        .form-body { padding: 28px; }

        .likert-header {
            display: grid;
            grid-template-columns: 1fr repeat(5, 64px);
            gap: 8px;
            align-items: center;
            padding: 10px 14px;
            background: #f1f5f9;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: .78em;
            font-weight: 800;
            color: var(--mu);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .likert-header span { text-align: center; }

        .q-item {
            display: grid;
            grid-template-columns: 1fr repeat(5, 64px);
            gap: 8px;
            align-items: center;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 8px;
            border: 1px solid var(--border);
            transition: .2s;
        }

        .q-item:hover { border-color: var(--np); background: rgba(139,92,246,.04); }

        .q-label {
            font-size: .9em;
            font-weight: 700;
            color: var(--tx);
            line-height: 1.4;
        }

        .q-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px; height: 22px;
            background: linear-gradient(135deg, var(--np), var(--nb));
            color: #fff;
            border-radius: 50%;
            font-size: .7em;
            font-weight: 900;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .radio-col { display: flex; justify-content: center; align-items: center; }

        .radio-col input[type="radio"] {
            width: 22px; height: 22px;
            accent-color: var(--np);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--np), var(--nb));
            color: #fff;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-family: 'Nunito';
            font-size: 1.05em;
            font-weight: 900;
            cursor: pointer;
            transition: .25s;
            margin-top: 24px;
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 24px rgba(139, 92, 246, .4);
        }

        .skala-desc {
            display: flex; gap: 8px; flex-wrap: wrap;
            margin-bottom: 20px; font-size: .8em;
        }

        .skala-chip {
            padding: 3px 10px; border-radius: 20px;
            border: 1px solid var(--border);
            color: var(--mu);
        }

        .alert-error {
            background: rgba(244,63,94,.1);
            border: 1px solid var(--nr);
            color: var(--nr);
            padding: 10px 16px; border-radius: 10px;
            margin-bottom: 16px; font-weight: 700;
        }

        @media (max-width: 600px) {
            .likert-header { grid-template-columns: 1fr repeat(5, 44px); }
            .q-item { grid-template-columns: 1fr repeat(5, 44px); }
        }
    </style>
</head>

<body>
    <div class="orb o1"></div>
    <div class="orb o2"></div>

    <div class="topbar">
        <span class="logo">⬡ SMP NEGERI 1 BENAI</span>
        <a href="{{ route('index') }}" class="btn-sm"><i class="fas fa-home"></i> Menu</a>
    </div>

    <div class="wrap">
        <div class="page-header">
            <div class="badge-jenis">📋 ANGKET {{ strtoupper($labelJenis) }}</div>
            <h1><i class="fas fa-clipboard-check"></i> Kuesioner Minat Belajar</h1>
            <p>Isi angket ini dengan jujur untuk membantu kami mengembangkan aplikasi yang lebih baik.</p>
        </div>

        @if(session('error'))
            <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @if($sudahIsi)
            <div class="done-box">
                <i class="fas fa-check-circle"></i>
                <h2>Sudah Diisi!</h2>
                <p>Kamu sudah mengisi kuesioner <strong>{{ $labelJenis }}</strong> ini sebelumnya. Terima kasih atas partisipasimu!</p>
                <br>
                <a href="{{ route('index') }}" class="btn-sm" style="margin:0 auto;">
                    <i class="fas fa-home"></i> Kembali ke Menu
                </a>
            </div>
        @else
            <div class="info-box">
                <strong><i class="fas fa-info-circle"></i> Petunjuk Pengisian:</strong><br>
                Baca setiap pernyataan dengan seksama, lalu pilih angka yang paling sesuai dengan kondisimu saat ini.<br>
                Setiap pernyataan harus diisi. Tidak ada jawaban yang benar atau salah.
            </div>

            <div class="form-card">
                <div class="form-card-header">
                    <h2><i class="fas fa-poll"></i> Angket {{ $labelJenis }} — Minat Belajar</h2>
                    <p>10 pernyataan | Skala Likert 1–5 | Wajib diisi semua</p>
                </div>
                <div class="form-body">
                    <div class="skala-desc">
                        <span class="skala-chip">1 = Sangat Tidak Setuju</span>
                        <span class="skala-chip">2 = Tidak Setuju</span>
                        <span class="skala-chip">3 = Netral</span>
                        <span class="skala-chip">4 = Setuju</span>
                        <span class="skala-chip">5 = Sangat Setuju</span>
                    </div>

                    <form action="{{ route('user.kuesioner.store', $jenis) }}" method="POST" id="kuesionerForm">
                        @csrf

                        <div class="likert-header">
                            <span>Pernyataan</span>
                            <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span>
                        </div>

                        @php
                        $pertanyaan = [
                            1  => 'Saya merasa senang belajar menggunakan aplikasi ini.',
                            2  => 'Aplikasi ini membuat saya lebih semangat dalam belajar.',
                            3  => 'Belajar dengan aplikasi ini lebih menyenangkan dibandingkan buku biasa.',
                            4  => 'Saya ingin terus menggunakan aplikasi ini untuk belajar.',
                            5  => 'Aplikasi ini membantu saya memahami materi pelajaran lebih mudah.',
                            6  => 'Saya merasa aplikasi ini menarik dan tidak membosankan.',
                            7  => 'Game dan kuis di aplikasi ini memotivasi saya untuk belajar lebih giat.',
                            8  => 'Saya aktif mengerjakan materi dan latihan yang ada di aplikasi ini.',
                            9  => 'Saya merasa lebih percaya diri setelah belajar dengan aplikasi ini.',
                            10 => 'Saya merekomendasikan aplikasi ini kepada teman-teman saya.',
                        ];
                        @endphp

                        @foreach($pertanyaan as $no => $teks)
                        <div class="q-item">
                            <div class="q-label">
                                <span class="q-num">{{ $no }}</span>{{ $teks }}
                            </div>
                            @for($v = 1; $v <= 5; $v++)
                            <div class="radio-col">
                                <input type="radio" name="jawaban[{{ $no }}]" value="{{ $v }}" required>
                            </div>
                            @endfor
                        </div>
                        @endforeach

                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="fas fa-paper-plane"></i> Kirim Jawaban
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('kuesionerForm')?.addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        });
    </script>
</body>
</html>
