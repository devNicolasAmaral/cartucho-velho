/*
  Arquivo JavaScript - pong.js (Adaptado para Injeção Dinâmica)
*/

(function() { // Função auto-executável para não sujar o escopo global

    // 1. A Interface Específica do Pong (HTML string)
    const pongHTML = `
        <div id="telaMenu" class="game-overlay">
            <h1 class="game-title">PONG.EXE</h1>
            <button id="btn1Jog">1 Jogador</button>
            <button id="btn2Jog">2 Jogadores</button>
            <div style="margin-top:20px; font-size:12px;">Controles: Setas / W-S</div>
        </div>

        <div id="telaFim" class="game-overlay hidden">
            <h1 class="game-title" id="msgFim">FIM</h1>
            <button id="btnReiniciar">Jogar Novamente</button>
        </div>

        <div class="score-board">
            <div id="p1Score" class="score-text">0</div>
            <div id="p2Score" class="score-text">0</div>
        </div>
        
        <canvas id="canvasJogo" width="650" height="480"></canvas>
    `;

    // 2. Inicialização: Injeta o HTML e configura variáveis
    const root = document.getElementById('game-root');
    document.getElementById('windowTitle').innerText = "Pong";
    root.innerHTML = pongHTML;

    // Referências aos elementos recém-criados
    const canvas = document.getElementById('canvasJogo');
    const ctx = canvas.getContext('2d');
    const menuScreen = document.getElementById('telaMenu');
    const endScreen = document.getElementById('telaFim');
    const p1ScoreElem = document.getElementById('p1Score');
    const p2ScoreElem = document.getElementById('p2Score');

    // --- LÓGICA DO JOGO (Simplificada para caber no exemplo) ---
    let gameRunning = false;
    let isSinglePlayer = true;
    let ball = { x: 325, y: 240, dx: 4, dy: 4, size: 15 };
    let p1 = { x: 30, y: 200, w: 15, h: 75, dy: 6, score: 0 };
    let p2 = { x: 605, y: 200, w: 15, h: 75, dy: 6, score: 0 };
    let keys = {};

    // Event Listeners
    document.getElementById('btn1Jog').onclick = () => startGame(true);
    document.getElementById('btn2Jog').onclick = () => startGame(false);
    document.getElementById('btnReiniciar').onclick = () => {
        endScreen.classList.add('hidden');
        menuScreen.classList.remove('hidden');
    };

    window.addEventListener('keydown', e => keys[e.key] = true);
    window.addEventListener('keyup', e => keys[e.key] = false);

    function startGame(singlePlayer) {
        isSinglePlayer = singlePlayer;
        p1.score = 0; p2.score = 0;
        resetBall();
        updateScore();
        menuScreen.classList.add('hidden');
        gameRunning = true;
        requestAnimationFrame(gameLoop);
    }

    function resetBall() {
        ball.x = canvas.width / 2;
        ball.y = canvas.height / 2;
        ball.dx = (Math.random() > 0.5 ? 4 : -4);
        ball.dy = (Math.random() > 0.5 ? 4 : -4);
    }

    function updateScore() {
        p1ScoreElem.innerText = p1.score;
        p2ScoreElem.innerText = p2.score;
    }

    function gameLoop() {
        if (!gameRunning) return;

        // Limpar tela
        ctx.fillStyle = "black";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Linha central
        ctx.fillStyle = "white";
        for(let i=10; i<canvas.height; i+=30) ctx.fillRect(canvas.width/2 - 1, i, 2, 10);

        // Movimento Player 1
        if (keys['w'] || keys['W']) p1.y -= p1.dy;
        if (keys['s'] || keys['S']) p1.y += p1.dy;
        p1.y = Math.max(0, Math.min(canvas.height - p1.h, p1.y));

        // Movimento Player 2 (ou IA)
        if (isSinglePlayer) {
            // IA simples
            let center = p2.y + p2.h/2;
            if (center < ball.y - 10) p2.y += 4;
            else if (center > ball.y + 10) p2.y -= 4;
        } else {
            if (keys['ArrowUp']) p2.y -= p2.dy;
            if (keys['ArrowDown']) p2.y += p2.dy;
        }
        p2.y = Math.max(0, Math.min(canvas.height - p2.h, p2.y));

        // Movimento Bola
        ball.x += ball.dx;
        ball.y += ball.dy;

        // Colisões Paredes
        if (ball.y <= 0 || ball.y + ball.size >= canvas.height) ball.dy *= -1;

        // Colisões Raquetes
        if (checkCollision(ball, p1) || checkCollision(ball, p2)) {
            ball.dx *= -1.05; // Acelera um pouco
            // Empurrar bola para fora da raquete para evitar "colar"
            if (ball.x < canvas.width/2) ball.x = p1.x + p1.w;
            else ball.x = p2.x - ball.size;
        }

        // Pontuação
        if (ball.x < 0) {
            p2.score++;
            resetBall();
            updateScore();
        } else if (ball.x > canvas.width) {
            p1.score++;
            resetBall();
            updateScore();
        }

        // Fim de Jogo (Ex: 5 pontos)
        if (p1.score >= 5 || p2.score >= 5) {
            gameRunning = false;
            document.getElementById('msgFim').innerText = p1.score >= 5 ? "JOGADOR 1 VENCEU" : "JOGADOR 2 VENCEU";
            endScreen.classList.remove('hidden');
        }

        // Desenhar
        ctx.fillRect(ball.x, ball.y, ball.size, ball.size);
        ctx.fillRect(p1.x, p1.y, p1.w, p1.h);
        ctx.fillRect(p2.x, p2.y, p2.w, p2.h);

        requestAnimationFrame(gameLoop);
    }

    function checkCollision(b, p) {
        return b.x < p.x + p.w && b.x + b.size > p.x &&
               b.y < p.y + p.h && b.y + b.size > p.y;
    }

})();