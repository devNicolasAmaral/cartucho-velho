/*
  Arquivo JavaScript - snake.js
  Versão 2.0 - Input Buffer (Zero Lag) + Ajuste de Velocidade
*/

(function() {
    // 1. ESTILOS (CSS Injetado - Mantido igual)
    const gameStyles = `
        @import url('https://fonts.googleapis.com/css2?family=VT323&display=swap');
        
        #snake-container {
            font-family: 'VT323', monospace;
            background-color: #000;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border: 2px inset #808080;
        }

        /* Efeito de Scanline */
        #snake-container::after {
            content: " ";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            z-index: 2;
            background-size: 100% 2px, 3px 100%;
            pointer-events: none;
        }

        .snake-header {
            width: 100%;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            color: #00ff00;
            text-shadow: 0 0 5px #00ff00;
            font-size: 24px;
            z-index: 3;
            border-bottom: 2px solid #333;
        }

        canvas {
            background-color: #000;
            display: block;
            z-index: 1;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.1);
        }

        /* Telas */
        .overlay-screen {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #00ff00;
            text-shadow: 2px 2px #003300;
            z-index: 10;
            background-color: rgba(0, 0, 0, 0.85);
            padding: 20px;
            border: 2px solid #00ff00;
            width: 80%;
        }

        .blink { animation: blinker 1s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }

        .btn-retro-green {
            background: #000;
            color: #00ff00;
            border: 2px solid #00ff00;
            font-family: 'VT323', monospace;
            font-size: 20px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 15px;
            text-transform: uppercase;
        }
        .btn-retro-green:hover {
            background: #00ff00;
            color: #000;
        }
    `;

    // Remove estilos antigos para evitar duplicidade
    const oldStyle = document.getElementById('snake-style');
    if (oldStyle) oldStyle.remove();

    const styleTag = document.createElement('style');
    styleTag.id = 'snake-style';
    styleTag.textContent = gameStyles;
    document.head.appendChild(styleTag);

    // 2. HTML (Interface)
    const snakeHTML = `
        <div id="snake-container">
            <div class="snake-header">
                <div>SCORE: <span id="scoreVal">0</span></div>
                <div>HIGHSCORE: <span id="highScoreVal">0</span></div>
            </div>
            
            <canvas id="snakeCanvas" width="600" height="400"></canvas>

            <div id="startScreen" class="overlay-screen">
                <h1 style="font-size: 40px; margin: 0;">SNAKE.EXE</h1>
                <p>Use as SETAS para mover</p>
                <button class="btn-retro-green blink" id="btnStart">INICIAR SISTEMA</button>
            </div>

            <div id="gameOverScreen" class="overlay-screen" style="display: none;">
                <h1 style="color: red; text-shadow: 2px 2px #330000;">FATAL ERROR</h1>
                <p>Sua cobra colidiu.</p>
                <p>Score Final: <span id="finalScore">0</span></p>
                <button class="btn-retro-green" id="btnRestart">REINICIAR</button>
            </div>
        </div>
    `;

    // Injeção no DOM
    const root = document.getElementById('game-root');
    document.getElementById('windowTitle').innerText = "Snake - Terminal Mode";
    root.innerHTML = snakeHTML;

    // 3. LÓGICA DO JOGO (Otimizada)
    const canvas = document.getElementById('snakeCanvas');
    const ctx = canvas.getContext('2d');
    
    // Referências DOM
    const startScreen = document.getElementById('startScreen');
    const gameOverScreen = document.getElementById('gameOverScreen');
    const scoreEl = document.getElementById('scoreVal');
    const highScoreEl = document.getElementById('highScoreVal');
    const finalScoreEl = document.getElementById('finalScore');
    const btnStart = document.getElementById('btnStart');
    const btnRestart = document.getElementById('btnRestart');

    // Configurações
    const box = 20; 
    const canvasW = canvas.width;
    const canvasH = canvas.height;
    const gameSpeed = 80; // (ms) Diminuí de 100 para 80 para ficar mais rápido/fluido

    // Variáveis de Estado
    let snake = [];
    let food = {};
    let score = 0;
    let d = ''; 
    let gameInterval;
    let highScore = localStorage.getItem('snake_highscore') || 0;
    
    // --- OTIMIZAÇÃO: FILA DE COMANDOS ---
    // Isso impede que o jogo "coma" comandos se você digitar muito rápido
    let inputQueue = []; 

    highScoreEl.innerText = highScore;

    // Controles
    document.addEventListener('keydown', handleInput);

    function handleInput(event) {
        let key = event.keyCode;
        
        // Direção prevista (baseada no último comando da fila ou na direção atual)
        let lastMove = inputQueue.length > 0 ? inputQueue[inputQueue.length - 1] : d;

        // Lógica de Direção (impede volta 180 graus baseada no futuro movimento)
        if(key == 37 && lastMove != 'RIGHT' && lastMove != 'LEFT') inputQueue.push('LEFT');
        else if(key == 38 && lastMove != 'DOWN' && lastMove != 'UP') inputQueue.push('UP');
        else if(key == 39 && lastMove != 'LEFT' && lastMove != 'RIGHT') inputQueue.push('RIGHT');
        else if(key == 40 && lastMove != 'UP' && lastMove != 'DOWN') inputQueue.push('DOWN');
        
        // Bloqueia scroll da página
        if([37, 38, 39, 40].indexOf(key) > -1) {
            event.preventDefault();
        }
    }

    function initGame() {
        startScreen.style.display = 'none';
        gameOverScreen.style.display = 'none';
        
        snake = [];
        snake[0] = { x: 9 * box, y: 10 * box }; 
        
        score = 0;
        scoreEl.innerText = score;
        
        d = 'RIGHT'; 
        inputQueue = []; // Limpa a fila ao reiniciar

        generateFood();
        
        if(gameInterval) clearInterval(gameInterval);
        gameInterval = setInterval(draw, gameSpeed);
    }

    function generateFood() {
        food = {
            x: Math.floor(Math.random() * (canvasW/box)) * box,
            y: Math.floor(Math.random() * (canvasH/box)) * box
        };
        // Garante que a comida não nasça dentro da cobra
        for(let i=0; i < snake.length; i++){
            if(food.x == snake[i].x && food.y == snake[i].y){
                generateFood();
            }
        }
    }

    function collision(head, array) {
        for(let i = 0; i < array.length; i++) {
            if(head.x == array[i].x && head.y == array[i].y) {
                return true;
            }
        }
        return false;
    }

    function draw() {
        // --- PROCESSA A FILA DE ENTRADA ---
        if(inputQueue.length > 0) {
            d = inputQueue.shift(); // Pega o próximo movimento da fila
        }

        // Limpa a tela
        ctx.fillStyle = '#000000';
        ctx.fillRect(0, 0, canvasW, canvasH);

        // Desenha Cobra
        for(let i = 0; i < snake.length; i++) {
            ctx.fillStyle = (i == 0) ? '#00FF00' : '#00CC00'; 
            ctx.fillRect(snake[i].x, snake[i].y, box, box);
            
            ctx.strokeStyle = '#000000';
            ctx.strokeRect(snake[i].x, snake[i].y, box, box);
        }

        // Desenha Comida
        ctx.fillStyle = '#FF0000';
        ctx.fillRect(food.x, food.y, box, box);

        // Movimento
        let snakeX = snake[0].x;
        let snakeY = snake[0].y;

        if(d == 'LEFT') snakeX -= box;
        if(d == 'UP') snakeY -= box;
        if(d == 'RIGHT') snakeX += box;
        if(d == 'DOWN') snakeY += box;

        // Comer Comida
        if(snakeX == food.x && snakeY == food.y) {
            score++;
            scoreEl.innerText = score;
            generateFood();
        } else {
            snake.pop();
        }

        // Nova Cabeça
        let newHead = { x: snakeX, y: snakeY };

        // Colisões (Paredes ou Próprio corpo)
        if(snakeX < 0 || snakeX >= canvasW || snakeY < 0 || snakeY >= canvasH || collision(newHead, snake)) {
            clearInterval(gameInterval);
            endGame();
            return;
        }

        snake.unshift(newHead);
    }

    function endGame() {
        if(score > highScore) {
            highScore = score;
            localStorage.setItem('snake_highscore', highScore);
            highScoreEl.innerText = highScore;
        }
        finalScoreEl.innerText = score;
        gameOverScreen.style.display = 'block';
    }

    btnStart.addEventListener('click', initGame);
    btnRestart.addEventListener('click', initGame);

})();