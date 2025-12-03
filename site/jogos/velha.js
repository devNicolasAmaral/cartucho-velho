/*
  Arquivo JavaScript - velha.js
  Integração dos modos PvP e PvCPU no console Win98
*/

(function() {
    // 1. IMPORTAR FONTE E ESTILOS ESPECÍFICOS DESTE JOGO
    // Injetamos o CSS dinamicamente para não poluir o CSS global do Windows 98
    const gameStyles = `
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
        
        #velha-container {
            font-family: 'Press Start 2P', cursive;
            background-color: #2D588F;
            color: white;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Menu Principal do Jogo */
        .velha-menu {
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: center;
            z-index: 10;
        }

        .velha-btn {
            font-family: 'Press Start 2P';
            background: linear-gradient(90deg, #469299 0%, #2D588F 100%);
            color: white;
            border: 3px solid white;
            padding: 15px;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 12px;
            box-shadow: 4px 4px 0px #000;
        }
        .velha-btn:hover {
            background: linear-gradient(90deg, #51a8b0 0%, #376aad 100%);
            transform: scale(1.05);
        }

        /* Tabuleiro */
        .game-area {
            display: none; /* Escondido até começar */
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .scoreboard {
            display: flex;
            justify-content: space-between;
            width: 80%;
            margin-bottom: 20px;
            font-size: 12px;
            text-shadow: 2px 2px #000;
        }

        .board-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border: 3px solid white;
            background-color: #2D588F;
        }

        .cell {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            cursor: pointer;
            border: 3px solid white;
            /* Remove bordas externas para ficar igual ao seu design */
            border-top: none;
            border-left: none;
        }
        /* Ajustes das bordas da grade */
        .cell:nth-child(3n) { border-right: none; }
        .cell:nth-child(n+7) { border-bottom: none; }

        .game-status {
            margin-top: 20px;
            font-size: 14px;
            height: 20px;
            color: #ef4556; /* Cor rosa do seu CSS */
            text-align: center;
        }

        .btn-small {
            margin-top: 20px;
            padding: 10px;
            font-size: 10px;
        }
    `;

    const styleTag = document.createElement('style');
    styleTag.textContent = gameStyles;
    document.head.appendChild(styleTag);

    // 2. TEMPLATE HTML (Interface)
    const velhaHTML = `
        <div id="velha-container">
            <div id="menuScreen" class="velha-menu">
                <h1 style="font-size: 24px; margin-bottom: 30px; text-shadow: 3px 3px #000;">JOGO DA VELHA</h1>
                <button class="velha-btn" id="btnPvP">Jogador vs Jogador</button>
                <button class="velha-btn" id="btnPvCPU">Jogador vs CPU</button>
            </div>

            <div id="gameScreen" class="game-area">
                <div class="scoreboard">
                    <div>X: <span id="scoreX">0</span></div>
                    <div style="color: #10A57F">Rodada: <span id="roundCount">1</span></div>
                    <div>O: <span id="scoreO">0</span></div>
                </div>

                <div class="board-grid">
                    <div class="cell" id="0"></div>
                    <div class="cell" id="1"></div>
                    <div class="cell" id="2"></div>
                    <div class="cell" id="3"></div>
                    <div class="cell" id="4"></div>
                    <div class="cell" id="5"></div>
                    <div class="cell" id="6"></div>
                    <div class="cell" id="7"></div>
                    <div class="cell" id="8"></div>
                </div>

                <div class="game-status" id="statusText">Vez do Jogador X</div>
                
                <button class="velha-btn btn-small" id="btnVoltarMenu">Voltar ao Menu</button>
            </div>
        </div>
    `;

    // Injetar no Console
    const root = document.getElementById('game-root');
    document.getElementById('windowTitle').innerText = "Jogo da Velha";
    root.innerHTML = velhaHTML;

    // 3. LÓGICA DO JOGO (Adaptada do seu main.js e vscomputer.js)
    
    // Elementos
    const menuScreen = document.getElementById('menuScreen');
    const gameScreen = document.getElementById('gameScreen');
    const cells = Array.from(document.getElementsByClassName('cell'));
    const statusText = document.getElementById('statusText');
    const scoreXElem = document.getElementById('scoreX');
    const scoreOElem = document.getElementById('scoreO');
    const roundElem = document.getElementById('roundCount');

    // Variáveis de Estado
    const playerX = "X";
    const playerO = "O";
    let currentPlayer = playerX;
    let spaces = Array(9).fill(null);
    let gameActive = true;
    let isVsCPU = false;
    let winsX = 0;
    let winsO = 0;
    let round = 1;

    // Event Listeners Menu
    document.getElementById('btnPvP').addEventListener('click', () => initGame(false));
    document.getElementById('btnPvCPU').addEventListener('click', () => initGame(true));
    document.getElementById('btnVoltarMenu').addEventListener('click', showMenu);

    function showMenu() {
        gameScreen.style.display = 'none';
        menuScreen.style.display = 'flex';
        resetStats();
    }

    function initGame(vsCPU) {
        isVsCPU = vsCPU;
        menuScreen.style.display = 'none';
        gameScreen.style.display = 'flex';
        restartRound();
    }

    function resetStats() {
        winsX = 0; winsO = 0; round = 1;
        scoreXElem.innerText = 0;
        scoreOElem.innerText = 0;
        roundElem.innerText = 1;
    }

    function restartRound() {
        spaces.fill(null);
        cells.forEach(cell => {
            cell.innerText = '';
            cell.style.backgroundColor = '#2D588F'; // Reseta cor de fundo
        });
        currentPlayer = playerX;
        gameActive = true;
        statusText.innerText = `Vez de ${currentPlayer}`;
    }

    // Lógica do Clique
    cells.forEach(cell => cell.addEventListener('click', handleClick));

    function handleClick(e) {
        const id = e.target.id;

        // Se a célula já tem valor ou jogo acabou, ignora
        if (!gameActive || spaces[id]) return;

        // Jogada do Humano
        playMove(id);

        if (isVsCPU && gameActive && currentPlayer === playerO) {
            // Pequeno delay para a CPU parecer que está "pensando"
            gameActive = false; // Trava o clique enquanto CPU pensa
            statusText.innerText = "Computador pensando...";
            
            setTimeout(() => {
                computerMove();
                gameActive = true; // Destrava
            }, 600); 
        }
    }

    function playMove(id) {
        spaces[id] = currentPlayer;
        document.getElementById(id).innerText = currentPlayer;

        if (checkWinner()) {
            endGame(false);
        } else if (!spaces.includes(null)) {
            endGame(true); // Empate
        } else {
            // Troca turno
            currentPlayer = currentPlayer === playerX ? playerO : playerX;
            statusText.innerText = `Vez de ${currentPlayer}`;
        }
    }

    function computerMove() {
        // Lógica simples: Pega espaços vazios
        let emptyCells = spaces.reduce((acc, curr, index) => {
            if (curr === null) acc.push(index);
            return acc;
        }, []);

        if (emptyCells.length > 0) {
            let randomIndex = Math.floor(Math.random() * emptyCells.length);
            playMove(emptyCells[randomIndex]);
        }
    }

    const winningPlays = [
        [0,1,2], [3,4,5], [6,7,8], // Linhas
        [0,3,6], [1,4,7], [2,5,8], // Colunas
        [0,4,8], [2,4,6]           // Diagonais
    ];

    function checkWinner() {
        for (const condition of winningPlays) {
            let [a, b, c] = condition;
            if (spaces[a] && spaces[a] === spaces[b] && spaces[a] === spaces[c]) {
                // Pinta as células vencedoras
                let winColor = (currentPlayer === playerX) ? '#51A8B0' : 'rgba(245, 79, 140, 0.93)';
                document.getElementById(a).style.backgroundColor = winColor;
                document.getElementById(b).style.backgroundColor = winColor;
                document.getElementById(c).style.backgroundColor = winColor;
                return true;
            }
        }
        return false;
    }

    function endGame(isDraw) {
        gameActive = false;
        
        if (isDraw) {
            statusText.innerText = "EMPATE!";
        } else {
            statusText.innerText = `${currentPlayer} VENCEU!`;
            if (currentPlayer === playerX) {
                winsX++;
                scoreXElem.innerText = winsX;
            } else {
                winsO++;
                scoreOElem.innerText = winsO;
            }
        }

        // Reinicia automaticamente após 2 segundos
        setTimeout(() => {
            round++;
            roundElem.innerText = round;
            restartRound();
        }, 2000);
    }

})();