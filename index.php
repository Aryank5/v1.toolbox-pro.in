<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Terminal</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Roboto+Mono&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #0b0f12;
            --panel: rgba(255, 255, 255, 0.03);
            --neon: #00ff7f;
            --cyan: #00e5ff;
            --mag: #ff3fa2;
            --glass: rgba(255, 255, 255, 0.04);
        }

        html,
        body {
            height: 100%;
            margin: 0;
            background: var(--bg);
            font-family: 'Roboto Mono', monospace;
            color: #cfeeea;
        }

        /* matrix background */
        canvas#rain {
            position: fixed;
            inset: 0;
            z-index: 0;
            opacity: 0.12;
            pointer-events: none;
        }

        .container {
            position: relative;
            z-index: 2;
            padding: 18px;
            max-width: 900px;
            margin: 0 auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px
        }

        .online {
            display: flex;
            gap: 8px;
            align-items: center;
            font-family: Orbitron, sans-serif
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--neon);
            box-shadow: 0 0 10px var(--neon)
        }

        .ticker {
            background: #000;
            border-radius: 16px;
            padding: 8px 12px;
            color: #fff;
            opacity: 0.9;
            font-size: 13px
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px
        }

        .card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.01));
            border-radius: 14px;
            padding: 18px;
            height: 170px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(0, 255, 127, 0.08);
        }

        .card:before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 127, 0.02), transparent);
            mix-blend-mode: screen;
        }

        .game-title {
            font-family: Orbitron, sans-serif;
            font-size: 18px;
            color: var(--cyan)
        }

        .slots {

            margin-top: 12px;
            background: #0b0f12;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.04);
            color: #fff
        }

        .predict-btn {
            padding: 10px 16px;
            border-radius: 10px;
            border: 2px solid var(--neon);
            background: transparent;
            color: var(--neon);
            cursor: pointer;
            font-weight: 700;
            backdrop-filter: blur(6px);
            transition: all .18s;
            box-shadow: 0 6px 20px rgba(0, 255, 127, 0.05);
            width: 100%;
            margin-top: 30px;
        }

        .predict-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 255, 127, 0.12)
        }

        /* terminal overlay */
        .terminal {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(2, 8, 10, 0.8), rgba(0, 0, 0, 0.9));
            padding: 16px;
            color: var(--neon);
            font-size: 13px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            transform: translateY(100%);
            transition: transform .45s cubic-bezier(.2, .9, .3, 1);
        }

        .terminal.show {
            transform: translateY(0);
        }

        .log {
            height: 100%;
            overflow: hidden;
            white-space: pre-line;
        }

        .progress {
            height: 12px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 6px;
        }

        .bar {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, var(--neon), var(--cyan));
            transition: width 2.8s linear
        }

        .result {
            font-size: 22px;
            font-family: Orbitron;
            margin-top: 8px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* glitch */
        @keyframes glitch {
            0% {
                transform: translateX(0)
            }

            20% {
                transform: translateX(-2px)
            }

            40% {
                transform: translateX(2px)
            }

            60% {
                transform: translateX(-2px)
            }

            80% {
                transform: translateX(2px)
            }

            100% {
                transform: translateX(0)
            }
        }

        .card:hover .game-title {
            animation: glitch .8s linear
        }

        /* small footer */
        .footer-note {
            margin-top: 18px;
            color: #7fa6a0;
            font-size: 13px
        }

        .hack-text {
            display: inline-block;
            margin-top: 8px;
            font-family: 'Roboto Mono', monospace;
            font-size: 12px;
            /* compact for cards */
            color: #9adfbf;
            height: 18px;
            white-space: nowrap;
            overflow: hidden;
            border-right: 1.5px solid #00ff7f;
            /* cursor */
            padding-right: 4px;
            vertical-align: middle;
            animation: blinkCursor .7s infinite;
        }

        @keyframes blinkCursor {
            50% {
                border-color: transparent;
            }
        }
    </style>

    <style>
        .slot-progress {
            width: 100%;
            /* padding: 6px 10px; */
            box-sizing: border-box;
            font-family: "Share Tech Mono", monospace;
            color: #00ff9d;
            text-shadow: 0 0 6px #00ff9d;
            margin-top: 20px;
        }

        .slot-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .slot-progress-left {
            text-transform: uppercase;
            font-weight: 600;
        }

        .slot-progress-bar {
            position: relative;
            width: 100%;
            height: 8px;
            background: rgba(0, 255, 100, 0.08);
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(0, 255, 150, 0.2);
            box-shadow: inset 0 0 10px rgba(0, 255, 120, 0.15);
        }

        .slot-progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #00ff9d, #00e5ff);
            box-shadow: 0 0 12px #00ff9d, 0 0 20px #00e5ff;
            transition: width 0.6s ease, background 0.4s ease, box-shadow 0.4s ease;
            animation: hackerGlow 2s infinite alternate;
        }

        @keyframes hackerGlow {
            from {
                filter: brightness(1) saturate(1);
            }

            to {
                filter: brightness(1.5) saturate(1.4);
            }
        }

        .user-info {
            width: 100%;
            box-sizing: border-box;
            font-family: "Share Tech Mono", monospace;
            color: #00ff9d;
            text-shadow: 0 0 6px #00ff9d;
            /* background: rgba(0, 0, 0, 0.25);/*/
            /* padding: 10px 12px; */
            border-radius: 10px;
            /* border: 1px solid rgba(0, 255, 150, 0.2); */
            /* box-shadow: inset 0 0 10px rgba(0, 255, 120, 0.15); */
            margin-top: 13px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            animation: hackerGlow 2s infinite alternate;
        }

        .user-info-item {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .user-info-item strong {
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Reuse hackerGlow animation */
        @keyframes hackerGlow {
            from {
                filter: brightness(1) saturate(1);
            }

            to {
                filter: brightness(1.5) saturate(1.4);
            }
        }

        .refresh-pulse {
            -webkit-box-align: center;
            -webkit-align-items: center;
            -moz-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -moz-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            position: relative;
            height: 20px;
            width: 20px;
            margin: 0 8px;
        }

        .dot {
            background-color: #b0c4de;
            position: absolute;
            border-radius: 50%;
            height: 14px;
            width: 14px;
        }

        .fresh {
            background-color: #00ff00;
        }

        .fresh2 {
            border-color: #90ee90;
        }

        .expanding-ring {
            /* border-color: #b0c4de; */
            border-style: solid;
            border-width: 2px;
            border-radius: 50%;
            height: 14px;
            opacity: 1;
            position: absolute;
            width: 14px;
            -webkit-animation-name: _ngcontent-ng-c4003862375_expanding;
            animation-name: _ngcontent-ng-c4003862375_expanding;
            -webkit-animation-duration: 2.5s;
            animation-duration: 2.5s;
            -webkit-animation-iteration-count: infinite;
            animation-iteration-count: infinite;
            -webkit-animation-timing-function: cubic-bezier(0, 0, .2, 1);
            animation-timing-function: cubic-bezier(0, 0, .2, 1);
        }


        @-webkit-keyframes _ngcontent-ng-c4003862375_expanding {
            0% {
                opacity: 1;
                -webkit-transform: scale(1);
                transform: scale(1)
            }

            to {
                opacity: 0;
                -webkit-transform: scale(1.5);
                transform: scale(1.5)
            }
        }

        .danmu-container {
            position: relative;
            width: 100%;
            height: 30px;
            margin-bottom: 15px;
            margin-top: 20px;
            overflow: hidden;
        }

        .danmu-container .danmu-message {
            position: absolute;
            width: fit-content;
            height: 24px;
            display: flex;
            flex-direction: row;
            align-items: center;
            backdrop-filter: blur(40px);
            padding: 0 8px;
            border-radius: 8px;
            left: 100%;
            /*cursor: pointer;*/
            transition: box-shadow 0.3s ease;
            box-sizing: border-box;

            background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.01));
            /* box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6); */
            border: 1px solid rgba(0, 255, 127, 0.08);

        }

        .danmu-container .danmu-message .message-content {
            color: #fff;
            /* font-family: Inter, sans-serif; */
            font-size: 11px;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 2px;
        }
    </style>

</head>

<body>
    <canvas id="rain"></canvas>
    <div class="container">
        <div class="top">
            <div class="online">
                <div _ngcontent-ng-c4003862375="" class="refresh-pulse">
                    <div _ngcontent-ng-c4003862375="" class="dot fresh">
                    </div>
                    <div _ngcontent-ng-c4003862375="" class="expanding-ring fresh2">
                    </div>
                </div>

                                <div>Online users: <span id="onlineCount" data-online="30">30</span>
                </div>
            </div>
        </div>

        <div class="danmu-container"></div>

        <div class="grid">

            
                <div class="card" data-name="Jalwa">
                    <div class="game-title">Jalwa</div>

                    <div class="hack-text">
                    </div>

                    
                        <div class="slot-progress">
                            <div class="slot-progress-header">
                                <span class="slot-progress-left">Slots Left</span>
                                <span class="slot-progress-right">23 / 25</span>
                            </div>
                            <div class="slot-progress-bar">
                                <div class="slot-progress-fill" style="width:92%">
                                </div>
                            </div>
                        </div>

                    
                    <button class="predict-btn" onclick="startPredict('Jalwa');"
                        data-link="https://jgdream.com/#/login">EXECUTE
                        NOW</button>

                    <div class="terminal">
                        <div class="log"> </div>
                        <div class="progress">
                            <div class="bar"></div>
                        </div>
                        <div class="result" style="opacity:0">
                            <div class="conf" style="font-size:13px;color:var(--neon)">Confidence: --</div>
                        </div>
                    </div>

                </div>

            
                <div class="card" data-name="55club">
                    <div class="game-title">55club</div>

                    <div class="hack-text">
                    </div>

                    
                        <div class="slot-progress">
                            <div class="slot-progress-header">
                                <span class="slot-progress-left">Slots Left</span>
                                <span class="slot-progress-right">25 / 25</span>
                            </div>
                            <div class="slot-progress-bar">
                                <div class="slot-progress-fill" style="width:100%">
                                </div>
                            </div>
                        </div>

                    
                    <button class="predict-btn" onclick="startPredict('55club');"
                        data-link="https://55club.app/#/login">EXECUTE
                        NOW</button>

                    <div class="terminal">
                        <div class="log"> </div>
                        <div class="progress">
                            <div class="bar"></div>
                        </div>
                        <div class="result" style="opacity:0">
                            <div class="conf" style="font-size:13px;color:var(--neon)">Confidence: --</div>
                        </div>
                    </div>

                </div>

            
                <div class="card" data-name="91club">
                    <div class="game-title">91club</div>

                    <div class="hack-text">
                    </div>

                    
                        <div class="slot-progress">
                            <div class="slot-progress-header">
                                <span class="slot-progress-left">Slots Left</span>
                                <span class="slot-progress-right">25 / 25</span>
                            </div>
                            <div class="slot-progress-bar">
                                <div class="slot-progress-fill" style="width:100%">
                                </div>
                            </div>
                        </div>

                    
                    <button class="predict-btn" onclick="startPredict('91club');"
                        data-link="https://91appe.com/#/login">EXECUTE
                        NOW</button>

                    <div class="terminal">
                        <div class="log"> </div>
                        <div class="progress">
                            <div class="bar"></div>
                        </div>
                        <div class="result" style="opacity:0">
                            <div class="conf" style="font-size:13px;color:var(--neon)">Confidence: --</div>
                        </div>
                    </div>

                </div>

            
                <div class="card" data-name="in999">
                    <div class="game-title">in999</div>

                    <div class="hack-text">
                    </div>

                    
                        <div class="slot-progress">
                            <div class="slot-progress-header">
                                <span class="slot-progress-left">Slots Left</span>
                                <span class="slot-progress-right">23 / 25</span>
                            </div>
                            <div class="slot-progress-bar">
                                <div class="slot-progress-fill" style="width:92%">
                                </div>
                            </div>
                        </div>

                    
                    <button class="predict-btn" onclick="startPredict('in999');"
                        data-link="https://in999aa.com/#/login">EXECUTE
                        NOW</button>

                    <div class="terminal">
                        <div class="log"> </div>
                        <div class="progress">
                            <div class="bar"></div>
                        </div>
                        <div class="result" style="opacity:0">
                            <div class="conf" style="font-size:13px;color:var(--neon)">Confidence: --</div>
                        </div>
                    </div>

                </div>

            
        </div>

        <!-- ===== Startup-style mini terminal (paste in place of your footer-note) ===== -->
        <div class="mini-terminal" id="miniTerminal">
            <div class="mini-terminal-header">
                <span>● SYSTEM TERMINAL</span>
                <span id="miniTerminalTime"></span>
            </div>
            <div class="mini-terminal-body" id="miniTerminalBody"></div>
        </div>

        <style>
            /* compact hacker terminal */
            .mini-terminal {
                position: relative;
                width: 100%;
                max-width: 940px;
                margin: 12px auto 0;
                font-family: 'Roboto Mono', monospace;
                background: linear-gradient(180deg, #031014, #000);
                border-radius: 10px;
                border: 1px solid rgba(0, 255, 127, 0.06);
                padding: 8px 10px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
                color: #bfeee0;
                box-sizing: border-box;
                overflow: hidden;
            }

            .mini-terminal-header {
                display: flex;
                justify-content: space-between;
                font-size: 12px;
                margin-bottom: 6px;
                color: rgba(180, 255, 200, 0.95);
            }

            .mini-terminal-body {
                height: 95px;
                overflow: hidden;
                padding: 6px;
                background: rgba(0, 0, 0, 0.25);
                border-radius: 8px;
                border: 1px solid rgba(255, 255, 255, 0.02);
                font-size: 12px;
                line-height: 1.35;
            }

            /* lines */
            .term-line {
                display: block;
                white-space: nowrap;
                opacity: 0;
                transform: translateY(6px);
                transition: all .28s ease;
            }

            .term-line.show {
                opacity: 1;
                transform: translateY(0);
            }

            .term-ts {
                font-size: 10px;
                color: rgba(180, 255, 200, 0.4);
                margin-right: 6px;
            }

            .term-green {
                color: #78ffb8;
            }

            .term-yellow {
                color: #ffe16b;
            }

            .term-cyan {
                color: #7fe8ff;
            }

            .cursor {
                display: inline-block;
                width: 6px;
                height: 10px;
                background: #00ff9d;
                animation: blink 0.7s steps(1) infinite;
                margin-left: 6px;
            }

            @keyframes blink {
                50% {
                    opacity: 0
                }
            }
        </style>

        <script>
            const termBody = document.getElementById('miniTerminalBody');
            const termTime = document.getElementById('miniTerminalTime');

            function now() { const d = new Date(); return d.toLocaleTimeString('en-GB', { hour12: false }); }
            termTime.textContent = now();
            setInterval(() => termTime.textContent = now(), 1000);

            /* startup lines (typed once, sequentially) */
            const startup = [
                [">> Initializing exploit engine...", "cyan"],
                [">> Loading VIP modules...", "cyan"],
                [">> Syncing model weights...", "yellow"],
                [">> Verifying RNG surfaces...", "cyan"],
                [">> Finalizing predictive core...", "yellow"]
            ];

            const finalLine = [">> SYSTEM READY — Ready to use", "green"];

            /* type a single line and return a Promise that resolves when done */
            function typeLine(text, color, revealDelay = 10) {
                return new Promise(resolve => {
                    const line = document.createElement('div');
                    line.className = `term-line term-${color}`;
                    const ts = document.createElement('span');
                    ts.className = 'term-ts';
                    ts.textContent = '[' + now() + ']';
                    const content = document.createElement('span');
                    line.appendChild(ts);
                    line.appendChild(content);
                    termBody.appendChild(line);
                    // keep only last 5 lines in DOM
                    while (termBody.children.length > 6) termBody.removeChild(termBody.firstChild);

                    // reveal container
                    requestAnimationFrame(() => setTimeout(() => line.classList.add('show'), 30));

                    // typing
                    let i = 0;
                    function step() {
                        if (i < text.length) {
                            content.textContent += text[i++];
                            setTimeout(step, revealDelay + Math.random() * 30);
                        } else {
                            // final small cursor flash
                            const cur = document.createElement('span'); cur.className = 'cursor'; content.appendChild(cur);
                            setTimeout(() => { if (cur.parentElement) cur.remove(); resolve(); }, 10);
                        }
                    }
                    setTimeout(step, 80);
                });
            }

            /* run startup sequence once */
            async function runStartup() {
                for (let i = 0; i < startup.length; i++) {
                    await typeLine(startup[i][0], startup[i][1]);
                    // small pause between lines
                    await new Promise(r => setTimeout(r, 10));
                }
                // final green ready line (no typing cursor, appear slightly after)
                await new Promise(r => setTimeout(r, 300));
                await typeLine(finalLine[0], finalLine[1], 12);
                // keep final line visible permanently (do not auto-remove)
            }
            runStartup();
        </script>

        <!-- Stylish hacker warning terminal -->
        <div class="hack-warning-terminal">
            <div class="warning-header">
                <span class="icon">⚠️</span>
                <span class="title">SYSTEM WARNING</span>
            </div>
            <div class="warning-body">
                <span class="arrow">>> </span>
                <span class="message">
                    Online gambling may cause financial loss.<br>
                    Please play responsibly and at your own risk.
                </span>
            </div>
        </div>

        <style>
            /* overall box */
            .hack-warning-terminal {
                margin-top: 18px;
                background: radial-gradient(circle at 20% 30%, #140000 0%, #050505 100%);
                border: 1px solid rgba(255, 40, 40, 0.4);
                border-left: 4px solid #ff4040;
                border-radius: 10px;
                padding: 14px 18px;
                font-family: "Courier New", monospace;
                color: #ff7070;
                box-shadow: 0 0 15px rgba(255, 0, 0, 0.25);
                width: fit-content;
                max-width: 90%;
                animation: fadeInBox 1.2s ease-out;
            }

            /* header line */
            .hack-warning-terminal .warning-header {
                display: flex;
                align-items: center;
                gap: 10px;
                border-bottom: 1px solid rgba(255, 50, 50, 0.2);
                padding-bottom: 6px;
                margin-bottom: 8px;
            }

            .hack-warning-terminal .icon {
                font-size: 20px;
                animation: blinkIcon 1s infinite;
            }

            .hack-warning-terminal .title {
                letter-spacing: 1px;
                font-weight: bold;
                color: #ff4040;
                text-shadow: 0 0 6px #ff0000;
            }

            /* body content */
            .hack-warning-terminal .warning-body {
                display: flex;
                align-items: flex-start;
                line-height: 1.5;
            }

            .hack-warning-terminal .arrow {
                color: #ff2020;
                text-shadow: 0 0 4px #ff0000;
                margin-right: 6px;
            }

            .hack-warning-terminal .message {
                color: #ff9a9a;
                text-shadow: 0 0 8px rgba(255, 50, 50, 0.3);
                animation: textGlitch 2.5s infinite;
            }

            /* subtle glitch/flicker effect */
            @keyframes textGlitch {

                0%,
                90%,
                100% {
                    opacity: 1;
                }

                92%,
                96% {
                    opacity: 0.6;
                }
            }

            /* blinking warning icon */
            @keyframes blinkIcon {
                50% {
                    opacity: 0.3;
                    transform: scale(1.1);
                }
            }

            /* smooth entry */
            @keyframes fadeInBox {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>

        <script>
            function updateSlotProgress() {
                document.querySelectorAll('.slot-progress-fill').forEach(fill => {
                    let width = parseFloat(fill.style.width) || 0;

                    if (width > 60) {
                        fill.style.background = "linear-gradient(90deg, #00ff9d, #00e5ff)";
                        fill.style.boxShadow = "0 0 12px #00ff9d, 0 0 20px #00e5ff";
                    } else if (width > 30) {
                        fill.style.background = "linear-gradient(90deg, #ffb300, #ff9900)";
                        fill.style.boxShadow = "0 0 12px #ffb300, 0 0 20px #ff9900";
                    } else {
                        fill.style.background = "linear-gradient(90deg, #ff0066, #ff3300)";
                        fill.style.boxShadow = "0 0 12px #ff0033, 0 0 20px #ff3300";
                    }
                });
            }
        </script>

        <script>

            function parsePhrases(attr) {
                if (!attr) return null;
                attr = attr.trim();
                try { const parsed = JSON.parse(attr); if (Array.isArray(parsed)) return parsed.map(s => String(s)); }
                catch (e) { }
                return attr.split(',').map(s => s.trim()).filter(Boolean);
            }

            function initHackText() {
                document.querySelectorAll('.hack-text').forEach((el, idx) => {
                    const raw = el.getAttribute('data-phrases');
                    const phrases = parsePhrases(raw) || [
                        ">> Initializing...",
                        ">> Fetching seed...",
                        ">> Process success."
                    ];
                    let cur = 0, char = 0;
                    const typeSpeed = 20, eraseSpeed = 28, pauseAfter = 500;

                    function typeChar() {
                        const txt = phrases[cur];
                        if (char <= txt.length) {
                            el.textContent = txt.substring(0, char);
                            char++;
                            setTimeout(typeChar, typeSpeed + Math.random() * 30);
                        } else {
                            // If this was the last phrase, STOP here (don't erase or continue)
                            if (cur === phrases.length - 1) {
                                // finished final phrase — do nothing more
                                return;
                            }
                            // otherwise proceed to erase as before
                            setTimeout(() => { setTimeout(eraseChar, 60); }, pauseAfter);
                        }
                    }
                    function eraseChar() {
                        const txt = phrases[cur];
                        if (char >= 0) {
                            el.textContent = txt.substring(0, char);
                            char--;
                            setTimeout(eraseChar, eraseSpeed + Math.random() * 20);
                        } else {
                            cur = (cur + 1) % phrases.length;
                            setTimeout(() => { char = 0; typeChar(); }, 250);
                        }
                    }
                    setTimeout(() => typeChar(), 120 * (idx + 1)); // stagger start
                });
            }

            // simple matrix rain
            const c = document.getElementById('rain'), ctx = c.getContext('2d');

            function resize() {
                const width = window.innerWidth || document.documentElement.clientWidth;
                const height = window.innerHeight || document.documentElement.clientHeight;

                // alert(innerWidth);
                if (width > 0 && height > 0) {

                    c.width = width;
                    c.height = height;

                    // alert("width "+ width);
                    // alert("height "+ height);
                } else {
                    // Try again shortly if WebView not ready yet
                    setTimeout(resize, 100);
                }
            }


            window.addEventListener('resize', resize); resize();
            const cols = Math.floor(c.width / 14); const drops = Array(cols).fill(0);

            function draw() {
                // console.log("DRAW");
                ctx.fillStyle = 'rgba(0,0,0,0.06)'; ctx.fillRect(0, 0, c.width, c.height);
                ctx.fillStyle = '#00ff7f'; ctx.font = '13px monospace';
                for (let i = 0; i < drops.length; i++) {
                    const text = String.fromCharCode(33 + Math.random() * 94);
                    ctx.fillText(text, i * 14, drops[i] * 14);
                    if (drops[i] * 14 > c.height && Math.random() > 0.975) drops[i] = 0;
                    drops[i]++;
                }
                requestAnimationFrame(draw);
            }


            function activeUsers() {
                setInterval(() => {
                    const onlineEl = document.getElementById("onlineCount");
                    if (!onlineEl) return;

                    let active = parseInt(onlineEl.getAttribute('data-online')) || 0;
                    //   max  min       min
                    active += Math.floor(Math.random() * (50 - 1 + 1)) + 1;;
                    onlineEl.innerText = active;
                }, 2500);
            }

            function androidRun() {
                if (typeof Android !== "undefined") {
                    const rootBg = getComputedStyle(document.documentElement).getPropertyValue('--bg').trim();

                    Android.changeStatusBarColor(rootBg, "light");
                    Android.changeNavigationBarColor(rootBg, "light");

                }
            }


            /* call once after DOM ready */
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function () {
                    initHackText();
                    draw();
                    updateSlotProgress();
                    androidRun();
                    activeUsers();
                });
            } else {
                initHackText();
                draw();
                updateSlotProgress();
                androidRun();
                activeUsers();
            }

            // prediction flow
            function startPredict(name) {

                const cart = document.querySelector('[data-name="' + name + '"]');

                if (cart) {
                    const term = cart.querySelector('.terminal');
                    if (term) term.classList.add('show');

                    const log = cart.querySelector('.log');
                    const bar = cart.querySelector('.bar');
                    const res = cart.querySelector('.result');
                    const confEl = cart.querySelector('.conf');

                    // replace this with your target URL
                    const redirectUrl = cart.querySelector('.predict-btn').getAttribute("data-link");

                    log.innerText = ">> Initializing exploit engine...\n>> Bypassing RNG layer...";
                    bar.style.width = '0%';
                    res.style.opacity = 0;

                    setTimeout(() => {
                        log.innerText += "\n>> Calculating high-probability outcome...";
                        bar.style.width = '68%';
                    }, 700);

                    setTimeout(() => {
                        bar.style.width = '100%';

                        // fake prediction
                        // const val = Math.floor(Math.random() * 99) + 1;
                        // instead of showing confidence, show short inject-success + redirect message
                        confEl.innerText = 'INJECT SUCCESS — Redirecting...';
                        res.style.opacity = 1;
                        // log.innerText += "\n>> Prediction ready. (" + val + ")";

                        // small success glow
                        term.animate(
                            [{ boxShadow: '0 0 0px rgba(0,255,127,0)' }, { boxShadow: '0 0 40px rgba(0,255,127,0.12)' }],
                            { duration: 700, fill: 'forwards' }
                        );

                        // redirect after a short pause (change delay if needed)
                        setTimeout(() => {
                            window.location.href = redirectUrl;
                        }, 800);

                        // optional: keep terminal visible a little while longer (cleanup fallback)
                        setTimeout(() => { term.classList.remove('show'); bar.style.width = '0%'; }, 3500);
                    }, 3200);

                } else {
                    console.warn('No element found for data-id:', name);
                }

            }


            // Random integer between min and max (inclusive)
            function rand(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            // Random multiple of 5 greater than 100
            function randMultipleOf5(min = 100, max = 1000000) {
                const n = rand(min, max);
                return Math.ceil(n / 5) * 5;
            }

            // Get the danmu container
            const Q = document.querySelector('.danmu-container');

            function ae(options, extraOptions) {
                const rowCount = Math.floor(Q.clientHeight / 30);
                const itemHeight = Q.clientHeight / rowCount;
                const settings = Object.assign({}, options, extraOptions);

                // Compute top position
                const topPos = Math.floor(Math.random() * rowCount) * itemHeight + (settings.top || 0);

                // Create danmu message
                const danmuMessage = document.createElement('div');
                danmuMessage.className = 'danmu-message';
                danmuMessage.style.top = topPos + 'px';

                // Create message content
                const messageContent = document.createElement('div');
                messageContent.className = 'message-content';
                messageContent.innerHTML = settings.danmu && settings.danmu.trim() !== "" ? settings.danmu : "Default message";

                danmuMessage.appendChild(messageContent);
                Q.appendChild(danmuMessage);

                // Compute total distance
                const itemWidth = Q.clientWidth + danmuMessage.offsetWidth;

                // Animate
                danmuMessage.style.transition = `transform ${settings.speed || 5}s linear`;
                danmuMessage.style.transform = `translateX(-${itemWidth}px)`;

                // Remove after animation
                danmuMessage.addEventListener('transitionend', () => {
                    danmuMessage.remove();
                });
            }

            // Run the danmu messages
            let index = 0;
            function runWithDelay() {
                const maxIndex = 20;

                if (index < maxIndex) {
                    const message = ['🎉', '⚡', '🚀', '💰'][Math.floor(Math.random() * 4)] + ` 91${rand(0, 9999)}****${rand(0, 99)} won ₹${randMultipleOf5(100, 5000)}!`;

                    ae({
                        danmu: message,
                        speed: 6,
                        top: 0
                    }, {});

                    index++;
                } else {
                    index = 0;
                }

                setTimeout(runWithDelay, 2500);
            }

            runWithDelay();

        </script>
</body>

</html>
