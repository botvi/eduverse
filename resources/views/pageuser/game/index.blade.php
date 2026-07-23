<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMP Negeri 1 Benai — Memory Match</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;700;900&family=Nunito:wght@400;700;900&display=swap"
        rel="stylesheet">
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            color: var(--tx);
            min-height: 100vh;
            padding: 0;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(rgba(14, 165, 233, .1) 1px, transparent 1px), linear-gradient(90deg, rgba(14, 165, 233, .1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: grid 20s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes grid {
            to {
                transform: translateY(50px)
            }
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: .13;
            pointer-events: none;
        }

        .o1 {
            width: 400px;
            height: 400px;
            background: var(--nb);
            top: -120px;
            left: -100px;
            animation: fl 9s ease-in-out infinite;
        }

        .o2 {
            width: 350px;
            height: 350px;
            background: var(--np);
            bottom: -100px;
            right: -80px;
            animation: fl 7s ease-in-out infinite reverse;
        }

        @keyframes fl {

            0%,
            100% {
                transform: translate(0, 0)
            }

            50% {
                transform: translate(20px, -20px)
            }
        }

        .topbar {
            position: sticky;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 24px;
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            z-index: 100;
        }

        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 1.1em;
            font-weight: 900;
            background: linear-gradient(90deg, var(--nb), var(--np));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 2px;
        }

        .topbar-right {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-sm {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--mu);
            padding: 7px 14px;
            border-radius: 8px;
            font-family: 'Nunito';
            font-size: .85em;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: .2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-sm:hover {
            border-color: var(--nb);
            color: var(--nb);
        }

        .mute-btn {
            border-color: var(--np);
            color: var(--np);
        }

        .mute-btn:hover {
            background: var(--np);
            color: #fff;
        }

        .wrap {
            max-width: 980px;
            margin: 0 auto;
            padding: 30px 16px;
            position: relative;
            z-index: 1;
        }

        /* LEVEL SELECT */
        #lvSel {
            text-align: center;
        }

        #lvSel h1 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(1.6rem, 4vw, 2.6rem);
            font-weight: 900;
            background: linear-gradient(135deg, var(--nb), var(--np), var(--ng));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
        }

        #lvSel p {
            color: var(--mu);
            margin-bottom: 30px;
        }

        .lv-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
            margin-bottom: 28px;
        }

        .lv-btn {
            width: 130px;
            height: 130px;
            border-radius: 20px;
            border: 2px solid;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            transition: .25s;
            font-family: 'Nunito';
            background: var(--card);
        }

        .lv-btn:hover:not(:disabled) {
            transform: translateY(-8px);
            filter: brightness(1.2);
        }

        .lv-btn:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .lv-num {
            font-family: 'Orbitron', monospace;
            font-size: 2em;
            font-weight: 900;
        }

        .lv-sub {
            font-size: .75em;
            font-weight: 700;
            opacity: .8;
        }

        .lv-stars {
            font-size: .9em;
        }

        .lv-stars .fa-star {
            color: var(--ny);
        }

        .lv-stars .empty {
            color: rgba(255, 255, 255, .2);
        }

        .c0 {
            border-color: #00c8ff;
            color: #00c8ff;
        }

        .c1 {
            border-color: #a855f7;
            color: #a855f7;
        }

        .c2 {
            border-color: #00ff88;
            color: #00ff88;
        }

        .c3 {
            border-color: #ffd700;
            color: #ffd700;
        }

        .c4 {
            border-color: #ff4f7b;
            color: #ff4f7b;
        }

        .c5 {
            border-color: #38bdf8;
            color: #38bdf8;
        }

        .c6 {
            border-color: #f472b6;
            color: #f472b6;
        }

        .c7 {
            border-color: #fb923c;
            color: #fb923c;
        }

        /* GAME */
        #game {
            display: none;
        }

        .ghdr {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 14px 20px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .gleft {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .glv {
            font-family: 'Orbitron', monospace;
            font-size: 1em;
            font-weight: 900;
            color: var(--nb);
        }

        .stats {
            display: flex;
            gap: 10px;
        }

        .stat {
            background: rgba(0, 200, 255, .08);
            border: 1px solid rgba(0, 200, 255, .2);
            color: var(--nb);
            padding: 6px 14px;
            border-radius: 10px;
            text-align: center;
            min-width: 72px;
        }

        .stat-l {
            font-size: .65em;
            opacity: .8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-v {
            font-family: 'Orbitron', monospace;
            font-size: 1.3em;
            font-weight: 900;
        }

        .prog-wrap {
            background: rgba(255, 255, 255, .05);
            border-radius: 20px;
            height: 6px;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .prog-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--nb), var(--np));
            border-radius: 20px;
            transition: width .4s;
        }

        .board {
            display: grid;
            gap: 10px;
            margin-bottom: 16px;
        }

        .card {
            background: transparent;
            aspect-ratio: 1;
            cursor: pointer;
            position: relative;
            transform-style: preserve-3d;
            transition: transform .5s;
        }

        .card:hover:not(.flipped):not(.matched) {
            transform: scale(1.06);
        }

        .card.flipped {
            transform: rotateY(180deg);
        }

        .card.matched {
            pointer-events: none;
        }

        .cf,
        .cb {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .cf {
            background: var(--card);
            border: 1px solid var(--border);
            color: var(--np);
            font-size: 2em;
            transition: .3s;
        }

        .card:hover:not(.flipped):not(.matched) .cf {
            border-color: var(--nb);
            box-shadow: 0 0 14px rgba(0, 200, 255, .2);
        }

        .cb {
            background: var(--card);
            border: 1px solid var(--nb);
            transform: rotateY(180deg);
            overflow: hidden;
            flex-direction: column;
        }

        .cb img {
            width: 100%;
            flex: 1;
            object-fit: cover;
            min-height: 0;
        }

        .cb-label {
            font-size: 0.6em;
            font-weight: 800;
            font-family: 'Nunito', sans-serif;
            color: var(--tx);
            background: rgba(255,255,255,0.92);
            text-align: center;
            padding: 3px 4px;
            line-height: 1.2;
            border-top: 1px solid var(--border);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            flex-shrink: 0;
        }

        .card.matched .cb {
            border-color: var(--ng);
            box-shadow: 0 0 16px rgba(0, 255, 136, .3);
            animation: mpop .4s ease;
        }

        @keyframes mpop {
            0% {
                transform: rotateY(180deg) scale(1)
            }

            50% {
                transform: rotateY(180deg) scale(1.12)
            }

            100% {
                transform: rotateY(180deg) scale(1)
            }
        }

        .ctrls {
            text-align: center;
        }

        .btn-game {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--mu);
            padding: 12px 28px;
            border-radius: 12px;
            font-family: 'Nunito';
            font-size: 1em;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .btn-game:hover {
            border-color: var(--nb);
            color: var(--nb);
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .75);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal.show {
            display: flex;
        }

        .mc {
            background: var(--card);
            border: 1px solid var(--nb);
            border-radius: 24px;
            padding: 36px 44px;
            text-align: center;
            max-width: 92%;
            animation: pop .45s cubic-bezier(.175, .885, .32, 1.275);
        }

        @keyframes pop {
            0% {
                transform: scale(.6);
                opacity: 0
            }

            70% {
                transform: scale(1.06)
            }

            100% {
                transform: scale(1);
                opacity: 1
            }
        }

        .mc h2 {
            font-family: 'Orbitron', monospace;
            font-size: 1.8em;
            font-weight: 900;
            background: linear-gradient(135deg, var(--nb), var(--np));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .mc-stars {
            font-size: 2.2em;
            margin: 10px 0;
        }

        .mc-stars .fa-star {
            color: var(--ny);
            filter: drop-shadow(0 0 6px var(--ny));
        }

        .mc-stars .empty {
            color: rgba(255, 255, 255, .15);
        }

        .mc-info {
            color: var(--mu);
            font-size: 1em;
            margin-bottom: 6px;
        }

        .mc-save {
            font-size: .82em;
            color: var(--mu);
            margin: 8px 0;
        }

        .mc-btns {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .btn-p {
            background: linear-gradient(135deg, var(--nb), var(--np));
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 12px;
            font-family: 'Nunito';
            font-size: 1em;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .btn-p:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(0, 200, 255, .4);
        }

        .btn-sec {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--mu);
            padding: 12px 24px;
            border-radius: 12px;
            font-family: 'Nunito';
            font-size: .95em;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .btn-sec:hover {
            border-color: var(--nb);
            color: var(--nb);
        }
    </style>
</head>

<body>
    <div class="orb o1"></div>
    <div class="orb o2"></div>

    <div class="topbar">
        <span class="logo">⬡ SMP NEGERI 1 BENAI</span>
        <div class="topbar-right">
            <button class="btn-sm mute-btn" id="muteBtn" onclick="toggleMute()"><i class="fas fa-volume-up"
                    id="muteIcon"></i> Musik</button>
            <a href="{{ route('index') }}" class="btn-sm"><i class="fas fa-home"></i> Menu</a>
        </div>
    </div>

    <div class="wrap">
        <!-- LEVEL SELECT -->
        <div id="lvSel">
            <h1><i class="fas fa-microchip"></i> MEMORY MATCH</h1>
            <p>Game memori futuristik — cocokkan semua kartu!</p>
            <div class="lv-grid" id="lvGrid"></div>
        </div>

        <!-- GAME -->
        <div id="game">
            <div class="ghdr">
                <div class="gleft">
                    <button class="btn-sm" onclick="goBack()"><i class="fas fa-arrow-left"></i></button>
                    <span class="glv" id="glvTitle">LEVEL 1</span>
                </div>
                <div class="stats">
                    <div class="stat">
                        <div class="stat-l">Moves</div>
                        <div class="stat-v" id="sMoves">0</div>
                    </div>
                    <div class="stat">
                        <div class="stat-l">Time</div>
                        <div class="stat-v" id="sTime">0:00</div>
                    </div>
                    <div class="stat">
                        <div class="stat-l">Match</div>
                        <div class="stat-v" id="sMatch">0/0</div>
                    </div>
                </div>
            </div>
            <div class="prog-wrap">
                <div class="prog-bar" id="progBar" style="width:0%"></div>
            </div>
            <div class="board" id="board"></div>
            <div class="ctrls"><button class="btn-game" onclick="restartLv()"><i class="fas fa-redo"></i> Ulangi
                    Level</button></div>
        </div>
    </div>

    <!-- WIN MODAL -->
    <div class="modal" id="winModal">
        <div class="mc">
            <h2><i class="fas fa-trophy"></i> LEVEL CLEAR!</h2>
            <div class="mc-stars" id="mcStars"></div>
            <div class="mc-info">Moves: <b id="fMoves"></b> &nbsp;|&nbsp; Time: <b id="fTime"></b></div>
            <div class="mc-save" id="mcSave"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</div>
            <div class="mc-btns">
                <button class="btn-sec" onclick="restartLv()"><i class="fas fa-redo"></i> Ulangi</button>
                <button class="btn-p" id="nextBtn" onclick="nextLv()"><i class="fas fa-forward"></i> Level
                    Berikutnya</button>
                <button class="btn-sec" onclick="goBack()"><i class="fas fa-th"></i> Pilih Level</button>
            </div>
        </div>
    </div>

    <script>
        // ── CONFIG ─────────────────────────────
        var SAVE_URL = "{{ route('user.game.save') }}";
        var CSRF = document.querySelector('meta[name="csrf-token"]').content;
        var PROG = @json($progressData); // from server per-user
        var TOTAL = 8;
        var LEVELS = Array.from({
            length: TOTAL
        }, (_, i) => ({
            level: i + 1,
            pairs: (i + 1) * 2 + 2
        }));
        var COLORS = ['c0', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7'];
        var IMGS = [
            'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Intel_i9-14900KF_CPU.jpg/330px-Intel_i9-14900KF_CPU.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/5/50/Xbox_360E_Winchester_Top_View.png/330px-Xbox_360E_Winchester_Top_View.png',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Generic_block_diagram_of_a_GPU.svg/330px-Generic_block_diagram_of_a_GPU.svg.png',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/d/db/Swissbit_2GB_PC2-5300U-555.jpg/330px-Swissbit_2GB_PC2-5300U-555.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Hatachi_500_GB_hard_drive%2C_2011.jpg/330px-Hatachi_500_GB_hard_drive%2C_2011.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/2023_Dysk_SSD_Patriot_P210_2TB.jpg/330px-2023_Dysk_SSD_Patriot_P210_2TB.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/PSU-Open1.jpg/330px-PSU-Open1.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/2/22/3-Tasten-Maus_Microsoft.jpg/330px-3-Tasten-Maus_Microsoft.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Typing_example.ogv/330px--Typing_example.ogv.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/7/76/MonitorLCDlcd.svg/330px-MonitorLCDlcd.svg.png',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e2/80mm_fan.jpg/330px-80mm_fan.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Gabinete99.jpg/330px-Gabinete99.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/Logicool_StreamCam_%28cropped%29.jpg/330px-Logicool_StreamCam_%28cropped%29.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/Shure_mikrofon_55S.jpg/330px-Shure_mikrofon_55S.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/ASR9006.jpg/330px-ASR9006.jpg',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/KL_Creative_Labs_Soundblaster_Live_Value_CT4670_%28cropped_and_transparent%29.png/330px-KL_Creative_Labs_Soundblaster_Live_Value_CT4670_%28cropped_and_transparent%29.png',
            'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Network_card.jpg/330px-Network_card.jpg',
            'https://i.pinimg.com/1200x/79/f0/c8/79f0c8f29e2d532a7fe72819a1940a3e.jpg',
        ];

        // Nama komponen untuk setiap gambar (urutan sama dengan IMGS)
        var IMG_NAMES = [
            'CPU (Prosesor)',
            'Hard Disk (HDD)',
            'GPU (Kartu Grafis)',
            'RAM (Memori)',
            'Hard Drive 500GB',
            'SSD (Solid State Drive)',
            'PSU (Catu Daya)',
            'Mouse',
            'Keyboard',
            'Monitor (LCD)',
            'Kipas (Fan)',
            'Casing Komputer',
            'Webcam',
            'Mikrofon',
            'Router / Switch',
            'Sound Card',
            'Network Card (LAN)',
            'Printer',
        ];

        // ── AUDIO ─────────────────────────────
        var AC, muted = false,
            bgNodes = [];

        function initAudio() {
            if (AC) return;
            AC = new(window.AudioContext || window.webkitAudioContext)();
            startBgMusic();
        }

        function beep(freq, dur, type, vol) {
            if (!AC || muted) return;
            var o = AC.createOscillator();
            var g = AC.createGain();
            o.connect(g);
            g.connect(AC.destination);
            o.type = type || 'sine';
            o.frequency.value = freq;
            g.gain.setValueAtTime(vol || 0.15, AC.currentTime);
            g.gain.exponentialRampToValueAtTime(0.001, AC.currentTime + dur);
            o.start();
            o.stop(AC.currentTime + dur);
        }

        function sfxFlip() {
            beep(440, .08, 'square', .08);
        }

        function sfxMatch() {
            beep(523, .12, 'sine', .15);
            setTimeout(() => beep(659, .15, 'sine', .15), 80);
            setTimeout(() => beep(784, .2, 'sine', .15), 160);
        }

        function sfxWrong() {
            beep(180, .25, 'sawtooth', .1);
        }

        function sfxWin() {
            [523, 659, 784, 1046].forEach((f, i) => setTimeout(() => beep(f, .3, 'sine', .18), i * 120));
        }

        function startBgMusic() {
            if (!AC || muted) return;
            stopBgMusic();
            // ambient arpeggio loop
            var notes = [261, 329, 392, 523, 392, 329];
            var idx = 0;

            function playNote() {
                if (muted || !AC) {
                    return;
                }
                var o = AC.createOscillator();
                var g = AC.createGain();
                o.connect(g);
                g.connect(AC.destination);
                o.type = 'triangle';
                o.frequency.value = notes[idx % notes.length];
                g.gain.setValueAtTime(0.04, AC.currentTime);
                g.gain.exponentialRampToValueAtTime(0.001, AC.currentTime + 0.4);
                o.start();
                o.stop(AC.currentTime + 0.45);
                idx++;
            }
            var id = setInterval(playNote, 480);
            bgNodes.push(id);
        }

        function stopBgMusic() {
            bgNodes.forEach(id => clearInterval(id));
            bgNodes = [];
        }

        function toggleMute() {
            initAudio();
            muted = !muted;
            var ic = document.getElementById('muteIcon');
            ic.className = muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
            if (muted) stopBgMusic();
            else startBgMusic();
        }

        // ── STATE ──────────────────────────────
        var curLv = 1,
            first = null,
            second = null;
        var canFlip = true,
            matches = 0,
            totalPairs = 0,
            moves = 0,
            secs = 0;
        var timerOn = false,
            timerID = null;

        // ── LEVEL SELECT ───────────────────────
        function buildLvSel() {
            var g = document.getElementById('lvGrid');
            g.innerHTML = '';
            LEVELS.forEach(function(cfg, i) {
                var lv = cfg.level,
                    p = cfg.pairs;
                var pr = PROG[lv] || {
                    unlocked: false,
                    stars: 0
                };
                var locked = !pr.unlocked;
                var b = document.createElement('button');
                b.className = 'lv-btn ' + COLORS[i % COLORS.length];
                b.disabled = locked;
                var sh = '';
                for (var s = 1; s <= 3; s++) sh += '<i class="fas fa-star' + (s > pr.stars ? ' empty' : '') +
                    '"></i>';
                b.innerHTML = '<div class="lv-num">' + lv + '</div>' +
                    '<div class="lv-sub">' + (locked ? '🔒 TERKUNCI' : p + ' pasang') + '</div>' +
                    '<div class="lv-stars">' + sh + '</div>';
                if (!locked)(function(n) {
                    b.onclick = function() {
                        initAudio();
                        startLv(n);
                    };
                })(lv);
                g.appendChild(b);
            });
        }

        // ── GAME ───────────────────────────────
        function startLv(lv) {
            curLv = lv;
            var cfg = LEVELS[lv - 1];
            totalPairs = cfg.pairs;
            document.getElementById('lvSel').style.display = 'none';
            document.getElementById('game').style.display = 'block';
            document.getElementById('winModal').classList.remove('show');
            document.getElementById('glvTitle').textContent = 'LEVEL ' + lv + ' — ' + totalPairs + ' PASANG';
            clearInterval(timerID);
            first = second = null;
            canFlip = true;
            matches = moves = secs = 0;
            timerOn = false;
            updateStats();
            buildBoard(totalPairs);
        }

        function buildBoard(pairs) {
            var b = document.getElementById('board');
            b.innerHTML = '';
            var idxPool = shuffle(Array.from({length: IMGS.length}, (_, i) => i)).slice(0, pairs);
            var cards = shuffle(idxPool.concat(idxPool));
            var cols = pairs <= 8 ? 4 : 5;
            b.style.gridTemplateColumns = 'repeat(' + cols + ',1fr)';
            cards.forEach(function(imgIdx) {
                var src  = IMGS[imgIdx];
                var name = IMG_NAMES[imgIdx] || '';
                var c = document.createElement('div');
                c.className = 'card';
                c.innerHTML = '<div class="cf"><i class="fas fa-microchip"></i></div>' +
                    '<div class="cb"><img src="' + src + '" loading="lazy"><span class="cb-label">' + name + '</span></div>';
                c.dataset.img = src;
                c.addEventListener('click', flip);
                b.appendChild(c);
            });
        }

        function flip() {
            if (!canFlip || this.classList.contains('flipped') || this.classList.contains('matched')) return;
            if (!timerOn) startTimer();
            sfxFlip();
            this.classList.add('flipped');
            if (!first) {
                first = this;
            } else {
                second = this;
                canFlip = false;
                moves++;
                updateStats();
                checkMatch();
            }
        }

        function checkMatch() {
            var ok = first.dataset.img === second.dataset.img && first !== second;
            var f = first,
                s = second;
            if (ok) {
                setTimeout(function() {
                    f.classList.add('matched');
                    s.classList.add('matched');
                    sfxMatch();
                    matches++;
                    updateStats();
                    reset();
                    if (matches === totalPairs) endGame();
                }, 350);
            } else {
                setTimeout(function() {
                    sfxWrong();
                    f.classList.remove('flipped');
                    s.classList.remove('flipped');
                    reset();
                }, 800);
            }
        }

        function reset() {
            first = second = null;
            canFlip = true;
        }

        function startTimer() {
            timerOn = true;
            timerID = setInterval(function() {
                secs++;
                updateStats();
            }, 1000);
        }

        function updateStats() {
            document.getElementById('sMoves').textContent = moves;
            document.getElementById('sMatch').textContent = matches + '/' + totalPairs;
            var m = Math.floor(secs / 60),
                s = secs % 60;
            document.getElementById('sTime').textContent = m + ':' + (s < 10 ? '0' : '') + s;
            document.getElementById('progBar').style.width = (matches / totalPairs * 100) + '%';
        }

        function calcStars(mv, pairs) {
            var r = mv / pairs;
            return r <= 1.5 ? 3 : r <= 2.3 ? 2 : 1;
        }

        function endGame() {
            clearInterval(timerID);
            sfxWin();
            var stars = calcStars(moves, totalPairs);
            var timeStr = document.getElementById('sTime').textContent;
            document.getElementById('fMoves').textContent = moves;
            document.getElementById('fTime').textContent = timeStr;
            var sh = '';
            for (var s = 1; s <= 3; s++) sh += '<i class="fas fa-star' + (s > stars ? ' empty' : '') + '"></i>';
            document.getElementById('mcStars').innerHTML = sh;
            document.getElementById('nextBtn').style.display = curLv >= TOTAL ? 'none' : 'inline-block';
            document.getElementById('winModal').classList.add('show');
            document.getElementById('mcSave').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            fetch(SAVE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        level: curLv,
                        stars: stars,
                        moves: moves,
                        time: timeStr
                    })
                })
                .then(r => r.json()).then(function(d) {
                    if (d.success) {
                        PROG[curLv] = {
                            unlocked: true,
                            stars: Math.max((PROG[curLv] || {
                                stars: 0
                            }).stars, stars),
                            best_moves: moves,
                            best_time: timeStr
                        };
                        if (curLv < TOTAL) {
                            PROG[curLv + 1] = PROG[curLv + 1] || {};
                            PROG[curLv + 1].unlocked = true;
                            PROG[curLv + 1].stars = PROG[curLv + 1].stars || 0;
                        }
                        document.getElementById('mcSave').innerHTML =
                            '<i class="fas fa-check" style="color:#00ff88"></i> Tersimpan!';
                    }
                }).catch(function() {
                    document.getElementById('mcSave').innerHTML =
                        '<i class="fas fa-exclamation-triangle" style="color:#ff4f7b"></i> Gagal simpan';
                });
        }

        function restartLv() {
            document.getElementById('winModal').classList.remove('show');
            startLv(curLv);
        }

        function nextLv() {
            document.getElementById('winModal').classList.remove('show');
            if (curLv < TOTAL) startLv(curLv + 1);
        }

        function goBack() {
            clearInterval(timerID);
            document.getElementById('winModal').classList.remove('show');
            document.getElementById('game').style.display = 'none';
            document.getElementById('lvSel').style.display = 'block';
            buildLvSel();
        }

        function shuffle(a) {
            for (var i = a.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var t = a[i];
                a[i] = a[j];
                a[j] = t;
            }
            return a;
        }

        // ── INIT ────────────────────────────────
        buildLvSel();
    </script>
</body>

</html>
