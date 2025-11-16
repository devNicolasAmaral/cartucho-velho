let restartBtn = document.getElementById("restartButton");
let cells = Array.from(document.getElementsByClassName("cell"));

const playerX = "X";
const playerO = "O";
let currentPlayer = playerX;
let spaces = Array(9).fill(null);
let player1Wins = 0; 
let player2Wins = 0;
let isGameStarted = false; 
let jogadorXRegistrado = false;

var cadastros = [];

function player1Register() {
    var player1 = document.getElementById("player1").value;
    if(player1 != ""){
        if (player1) {
            var cadastroX = { player1: player1};
            cadastros.push(cadastroX);
            jogadorXRegistrado = true;
        }
    
        var jogador1Text = document.querySelector("#jogador1Text p");
        jogador1Text.innerHTML = player1;
    
        removerSide1();
        player2Register();
    } else {
        alert("Entre com um nome para iniciar!")
        return;
    }
    
    removerSide1();
    player2Register();
}

function player2Register() {
    var player2 = true;
    if (player2) {
        var cadastroO = { player2: player2};
        cadastros.push(cadastroO);
    }

    checkStartGame();
}

function removerSide1() {
    var btnCad1 = document.getElementById("elementcadPlayer1");
    var inputCad1 = document.getElementById("player1");

    if (btnCad1 && btnCad1.parentNode) {
        btnCad1.parentNode.removeChild(btnCad1);
    }
    if (inputCad1 && inputCad1.parentNode) {
        inputCad1.parentNode.removeChild(inputCad1);
    }
}

function checkStartGame() {
    isGameStarted = true;
    startGame(isGameStarted);
}

const startGame = (isGameStarted) => {
    if (!isGameStarted) {
        return;
    }
}

function cellClick(idHtml) {
    const id = idHtml;
    
    if (!jogadorXRegistrado || spaces[id] !== null) {
        return;
    }

    console.log(playerX);
    console.log(playerO);
    
    spaces[id] = currentPlayer;
    document.getElementById(id).innerText = currentPlayer;
    
    if (checkWinner() !== false){
        jogadorXRegistrado = false;
        
        if ((checkWinner() !== false)&&(currentPlayer = playerX)) {
            let winningCells = checkWinner();
            
            winningCells.forEach(index => {
                document.getElementById(index).style.backgroundColor = '#51A8B0';
            });
            
                player1Wins++;
                document.getElementById('player1-wins').innerText = player1Wins;
                
                var jogador1Text = document.querySelector("#jogador1Text p");
                
                var nameWinner = document.querySelector("#titleText p");
                nameWinner.innerHTML = `${jogador1Text.innerHTML} venceu!`;
            
    
        } else if ((checkWinner() !== false)&&(currentPlayer = playerO)) {        
            let winningCells = checkWinner();
            
            winningCells.forEach(index => {
                document.getElementById(index).style.backgroundColor = 'rgba(245, 79, 140, 0.93)';
            });
            
                player2Wins++;
                document.getElementById('player2-wins').innerText = player2Wins;
                
                var nameWinner = document.querySelector("#titleText p");
                nameWinner.innerHTML = `A velha venceu!`;
            }
    
            setTimeout(() => {
                restartRodada();
            }, 3000);
            
            return;
        
        } else if (!spaces.includes(null)) {
        jogadorXRegistrado = false;
        var nameWinner = document.querySelector("#titleText p");
        nameWinner.innerHTML = `Empate!`;
        setTimeout(() => {
            restartRodada();
        }, 3000);
        return;
    }
    
    currentPlayer = currentPlayer === playerX ? playerO : playerX;
   
    if (currentPlayer === playerO) {
        computerMove();
        currentPlayer = playerX;

        if ((checkWinner() !== false)&&(currentPlayer = playerO)) {
            jogadorXRegistrado = false;
            let winningCells = checkWinner();
            
            winningCells.forEach(index => {
                document.getElementById(index).style.backgroundColor = 'rgba(245, 79, 140, 0.93)';
            });
            
            player2Wins++;
            document.getElementById('player2-wins').innerText = player2Wins;
            
            var nameWinner = document.querySelector("#titleText p");
            nameWinner.innerHTML = `A velha venceu!`;
            
            setTimeout(() => {
                restartRodada();
            }, 3000);
        } 
    } 
}

function computerMove() {
    let emptyCells = spaces.reduce((acc, curr, index) => {
        if (curr === null) {
            acc.push(index);
        }
        return acc;
    }, []);

    let randomIndex = Math.floor(Math.random() * emptyCells.length);
    let computerChoice = emptyCells[randomIndex];

    spaces[computerChoice] = playerO;
    document.getElementById(computerChoice).innerText = playerO;
}

const winningPlays = [
    [0,1,2],
    [3,4,5],
    [6,7,8],
    [0,3,6],
    [1,4,7],
    [2,5,8],
    [0,4,8],
    [2,4,6]
]

function checkWinner() {
    
    for (const youWon of winningPlays) {
        let [a, b, c] = youWon;

        if(spaces[a] && (spaces[a] == spaces[b] && spaces[a] == spaces[c])) {
            return [a,b,c];
        }

    }
    return false;
}

function restartRodada() {
    spaces.fill(null);

    const cellElements = document.querySelectorAll('.cell');
    cellElements.forEach(cell => {
        cell.innerText = '';
        cell.style.backgroundColor = '';
    });

    currentPlayer = playerX;
    jogadorXRegistrado = true;

    let round = parseInt(document.querySelector("#rodadaCount p").innerText);
    round += 1;
    document.querySelector("#rodadaCount p").innerText = round;
    
    var nameWinner = document.querySelector("#titleText p");
    nameWinner.innerHTML = "Jogo da Velha";
}

startGame();