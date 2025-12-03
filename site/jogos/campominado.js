/*
  Arquivo JavaScript - campo-minado.js
  Versão 2.0 - Com níveis, cores corrigidas e rostinho arrumado.
*/

(function() {
    // 1. ESTILOS (CSS Injetado)
    const gameStyles = `
        @import url('https://fonts.googleapis.com/css2?family=VT323&display=swap');
        
        #minesweeper-container {
            font-family: 'VT323', monospace;
            background-color: #c0c0c0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start; /* Alinhado ao topo para caber o menu */
            user-select: none;
            padding: 10px;
        }

        /* Menu de Dificuldade */
        .difficulty-menu {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            font-family: 'Pixelify Sans', sans-serif;
            font-size: 14px;
        }
        .diff-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #000;
            text-decoration: underline;
        }
        .diff-btn.active {
            color: #000080;
            font-weight: bold;
            text-decoration: none;
            border: 1px dotted #000;
        }

        .mine-window {
            background-color: #c0c0c0;
            border: 3px solid white;
            border-right-color: #808080;
            border-bottom-color: #808080;
            padding: 6px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
            max-width: 100%; /* Garante que não estoure */
        }

        /* Cabeçalho com display e carinha */
        .mine-header {
            border: 2px solid #808080;
            border-right-color: white;
            border-bottom-color: white;
            padding: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #c0c0c0;
            margin-bottom: 5px;
        }

        .digital-display {
            background-color: black;
            color: red;
            font-size: 28px;
            font-family: 'VT323', monospace;
            padding: 2px 4px;
            width: 50px;
            text-align: right;
            border: 1px solid #808080;
            border-right-color: white;
            border-bottom-color: white;
            line-height: 1;
        }

        .face-btn {
            width: 35px;
            height: 35px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            border-right-color: #808080;
            border-bottom-color: #808080;
            background-color: #c0c0c0;
            cursor: pointer;
            padding-bottom: 3px;
        }
        .face-btn:active {
            border: 2px solid #808080;
            border-right-color: white;
            border-bottom-color: white;
            transform: translateY(1px);
        }

        /* O Grid do jogo */
        .mine-grid {
            display: grid;
            gap: 0;
            border: 3px solid #808080;
            border-right-color: white;
            border-bottom-color: white;
            margin: 0 auto;
        }

        .mine-cell {
            width: 25px; /* Reduzi um pouco para caber o modo difícil */
            height: 25px;
            background-color: #c0c0c0;
            border: 2px solid white;
            border-right-color: #808080;
            border-bottom-color: #808080;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 900; /* Negrito mais forte estilo win98 */
            cursor: default;
            font-family: 'Verdana', sans-serif; /* Fonte padrão do minesweeper antigo */
        }

        /* Efeito ao clicar/revelar */
        .mine-cell.revealed {
            border: 1px solid #7b7b7b; /* Borda fina cinza escuro */
            background-color: #c0c0c0;
        }
        
        /* CORES DOS NÚMEROS (Solicitação Personalizada) */
        .c1 { color: blue; }
        .c2 { color: green; }
        .c3 { color: red; }
        .c4 { color: purple; }  
        .c5 { color: #cc9900; } /* Amarelo escuro para leitura no cinza */
        .c6 { color: teal; }
        .c7 { color: black; }
        .c8 { color: gray; }

        .mine-cell.bomb-exploded {
            background-color: red;
            border: 1px solid #808080;
        }
    `;

    // Remove estilos anteriores se houver (para não duplicar em recarregamentos SPA)
    const oldStyle = document.getElementById('minesweeper-style');
    if (oldStyle) oldStyle.remove();

    const styleTag = document.createElement('style');
    styleTag.id = 'minesweeper-style';
    styleTag.textContent = gameStyles;
    document.head.appendChild(styleTag);

    // 2. HTML (Interface)
    const minesHTML = `
        <div id="minesweeper-container">
            <div class="difficulty-menu">
                <button class="diff-btn" id="btnEasy">Fácil</button>
                <button class="diff-btn active" id="btnMedium">Médio</button>
                <button class="diff-btn" id="btnHard">Difícil</button>
            </div>

            <div class="mine-window">
                <div class="mine-header">
                    <div class="digital-display" id="bombCounter">000</div>
                    <button class="face-btn" id="resetFace">🙂</button>
                    <div class="digital-display" id="timer">000</div>
                </div>
                
                <div class="mine-grid" id="grid">
                    </div>
            </div>
            <div style="margin-top: 10px; font-size: 12px; text-align:center;">
                Botão Esq: Revelar | Botão Dir: Bandeira
            </div>
        </div>
    `;

    // Injeção no DOM
    const root = document.getElementById('game-root');
    document.getElementById('windowTitle').innerText = "Campo Minado.exe";
    root.innerHTML = minesHTML;

    // 3. CONFIGURAÇÕES E LÓGICA
    
    // Configurações dos Níveis
    const levels = {
        easy: { w: 8, h: 8, mines: 8 },
        medium: { w: 10, h: 10, mines: 12 },
        hard: { w: 20, h: 10, mines: 30 } // Mais largo, mesma altura
    };

    let currentConfig = levels.medium; // Começa no médio
    let grid = [];
    let isGameOver = false;
    let flags = 0;
    let seconds = 0;
    let timerInterval = null;
    let firstClick = true;

    // Referências do DOM
    const gridElement = document.getElementById('grid');
    const bombCounterEl = document.getElementById('bombCounter');
    const timerEl = document.getElementById('timer');
    const faceBtn = document.getElementById('resetFace');
    
    // Botões de Nível
    const btnEasy = document.getElementById('btnEasy');
    const btnMedium = document.getElementById('btnMedium');
    const btnHard = document.getElementById('btnHard');

    function setLevel(levelName) {
        currentConfig = levels[levelName];
        
        // Atualiza classes dos botões
        [btnEasy, btnMedium, btnHard].forEach(btn => btn.classList.remove('active'));
        if(levelName === 'easy') btnEasy.classList.add('active');
        if(levelName === 'medium') btnMedium.classList.add('active');
        if(levelName === 'hard') btnHard.classList.add('active');

        initGame();
    }

    // Listeners do Menu
    btnEasy.onclick = () => setLevel('easy');
    btnMedium.onclick = () => setLevel('medium');
    btnHard.onclick = () => setLevel('hard');


    function initGame() {
        clearInterval(timerInterval);
        gridElement.innerHTML = '';
        grid = [];
        isGameOver = false;
        flags = 0;
        seconds = 0;
        firstClick = true;
        
        // Configura o CSS Grid dinamicamente baseado na largura do nível
        gridElement.style.gridTemplateColumns = `repeat(${currentConfig.w}, 1fr)`;

        bombCounterEl.innerText = formatNumber(currentConfig.mines);
        timerEl.innerText = '000';
        faceBtn.innerText = '🙂';

        // Cria grid vazio
        for (let i = 0; i < currentConfig.w * currentConfig.h; i++) {
            const cell = document.createElement('div');
            cell.classList.add('mine-cell');
            cell.setAttribute('id', i);
            
            // Mouse Down para fazer a carinha "Ooo"
            cell.addEventListener('mousedown', (e) => {
                if (!isGameOver && e.button === 0) faceBtn.innerText = '😮';
            });
            // Mouse Up volta ao normal (será sobrescrito se houver game over)
            document.addEventListener('mouseup', () => {
                 if (!isGameOver) faceBtn.innerText = '🙂';
            }, { once: true }); // Executa apenas uma vez por clique

            cell.addEventListener('click', () => handleClick(cell));
            cell.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                addFlag(cell);
            });
            gridElement.appendChild(cell);
            grid.push({
                element: cell,
                hasBomb: false,
                revealed: false,
                flagged: false,
                nearbyBombs: 0
            });
        }
    }

    function startGame(startIndex) {
        // Coloca bombas aleatórias, exceto no local do primeiro clique
        let bombsPlaced = 0;
        const totalCells = currentConfig.w * currentConfig.h;

        while (bombsPlaced < currentConfig.mines) {
            const randomIdx = Math.floor(Math.random() * totalCells);
            // Evita colocar bomba onde clicou E onde já tem bomba
            if (!grid[randomIdx].hasBomb && randomIdx !== startIndex) {
                grid[randomIdx].hasBomb = true;
                bombsPlaced++;
            }
        }

        // Calcula números vizinhos
        for (let i = 0; i < grid.length; i++) {
            if (!grid[i].hasBomb) {
                grid[i].nearbyBombs = countNearbyBombs(i);
            }
        }

        // Inicia Timer
        timerInterval = setInterval(() => {
            if (seconds < 999) seconds++;
            timerEl.innerText = formatNumber(seconds);
        }, 1000);
    }

    function handleClick(cell) {
        if (isGameOver) return;
        const index = parseInt(cell.id);
        const cellData = grid[index];

        if (cellData.flagged || cellData.revealed) return;

        if (firstClick) {
            firstClick = false;
            startGame(index);
        }

        if (cellData.hasBomb) {
            gameOver(cellData);
        } else {
            revealCell(index);
            checkWin();
        }
    }

    function addFlag(cell) {
        if (isGameOver) return;
        const index = parseInt(cell.id);
        const cellData = grid[index];

        if (!cellData.revealed) {
            if (!cellData.flagged && flags < currentConfig.mines) {
                cellData.flagged = true;
                cell.innerHTML = '<span style="color:red">🚩</span>';
                flags++;
            } else if (cellData.flagged) {
                cellData.flagged = false;
                cell.innerHTML = '';
                flags--;
            }
            bombCounterEl.innerText = formatNumber(currentConfig.mines - flags);
        }
    }

    function revealCell(index) {
        const cellData = grid[index];
        if (cellData.revealed || cellData.flagged) return;

        cellData.revealed = true;
        cellData.element.classList.add('revealed');

        if (cellData.nearbyBombs > 0) {
            cellData.element.innerText = cellData.nearbyBombs;
            cellData.element.classList.add(`c${cellData.nearbyBombs}`);
        } else {
            // Flood Fill (Recursivo) para abrir áreas vazias
            const neighbors = getNeighbors(index);
            neighbors.forEach(neighborIndex => {
                if (!grid[neighborIndex].hasBomb && !grid[neighborIndex].revealed) {
                    revealCell(neighborIndex);
                }
            });
        }
    }

    function countNearbyBombs(index) {
        let count = 0;
        const neighbors = getNeighbors(index);
        neighbors.forEach(idx => {
            if (grid[idx].hasBomb) count++;
        });
        return count;
    }

    function getNeighbors(index) {
        const neighbors = [];
        const w = currentConfig.w;
        const total = w * currentConfig.h;
        
        const isLeftEdge = (index % w === 0);
        const isRightEdge = (index % w === w - 1);

        if (index - w >= 0) neighbors.push(index - w); // Top
        if (index + w < total) neighbors.push(index + w); // Bottom
        if (!isLeftEdge) neighbors.push(index - 1); // Left
        if (!isRightEdge) neighbors.push(index + 1); // Right
        
        if (!isLeftEdge && index - w >= 0) neighbors.push(index - w - 1); // Top Left
        if (!isRightEdge && index - w >= 0) neighbors.push(index - w + 1); // Top Right
        if (!isLeftEdge && index + w < total) neighbors.push(index + w - 1); // Bottom Left
        if (!isRightEdge && index + w < total) neighbors.push(index + w + 1); // Bottom Right

        return neighbors;
    }

    function gameOver(clickedCellData) {
        isGameOver = true;
        clearInterval(timerInterval);
        
        // Força o rosto de derrota IMEDIATAMENTE e previne mudanças
        faceBtn.innerText = '😵';

        grid.forEach(data => {
            if (data.hasBomb) {
                data.element.innerHTML = '💣';
                if (data === clickedCellData) {
                    data.element.classList.add('bomb-exploded');
                }
            } else if (data.flagged) {
                // Se marcou bandeira onde não tinha bomba (erro)
                data.element.innerHTML = '❌'; 
            }
        });
    }

    function checkWin() {
        let revealedCount = 0;
        grid.forEach(data => {
            if (data.revealed) revealedCount++;
        });

        // Vitória se o número de revelados + minas = total de células
        if (revealedCount === (currentConfig.w * currentConfig.h) - currentConfig.mines) {
            isGameOver = true;
            clearInterval(timerInterval);
            faceBtn.innerText = '😎'; // Vitória garantida
            bombCounterEl.innerText = 'WIN';
            
            // Marca bandeiras restantes automaticamente
            grid.forEach(data => {
                if (data.hasBomb && !data.flagged) {
                    data.element.innerHTML = '<span style="color:red">🚩</span>';
                }
            });
        }
    }

    function formatNumber(num) {
        if (num < 0) return "000";
        return num.toString().padStart(3, '0');
    }

    faceBtn.addEventListener('click', initGame);

    // Iniciar jogo
    initGame();
})();