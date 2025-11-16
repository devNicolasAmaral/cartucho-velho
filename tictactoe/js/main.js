let restartBtn = document.getElementById("restartButton");
let cells = Array.from(document.getElementsByClassName("cell"));
let winnerColor = '#51A8B0';

const playerX = "X";
const playerO = "O";
let currentPlayer = playerX;
let spaces = Array(9).fill(null);
let player1Wins = 0; 
let player2Wins = 0;
let isGameStarted = false; 

var cadastros = [];

function player1Register() {
    var player1 = document.getElementById("player1").value;

    if(player1 != ""){
        if (player1) {
            var cadastroX = { player1: player1};
            cadastros.push(cadastroX);
        }
    
        var jogador1Text = document.querySelector("#jogador1Text p");
        jogador1Text.innerHTML = player1;
    
        removerSide1();
        checkStartGame();
    } else {
        alert("Entre com um nome para iniciar!")
        return;
    }
}

function player2Register() {
    var player2 = document.getElementById("player2").value;

    if(player2 != ""){
        if (player2) {
            var cadastroO = { player2: player2};
            cadastros.push(cadastroO);
        }
    
        var jogador2Text = document.querySelector("#jogador2Text p");
        jogador2Text.innerHTML = player2;
    
        removerSide2();
        checkStartGame();
    } else {
        alert("Entre com um nome para iniciar!")
        return;
    }
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

function removerSide2() {
    var btnCad2 = document.getElementById("elementcadPlayer2");
    var inputCad2 = document.getElementById("player2");

    if (btnCad2 && btnCad2.parentNode) {
        btnCad2.parentNode.removeChild(btnCad2);
    }
    if (inputCad2 && inputCad2.parentNode) {
        inputCad2.parentNode.removeChild(inputCad2);
    }
}

function checkStartGame() {
    if (cadastros.length === 2) {
        isGameStarted = true;
        startGame();
    }
}

const startGame = () => {
    if (!isGameStarted) {
        return;
    }
    cells.forEach(cell => cell.addEventListener('click', cellClick))
}

function cellClick(e) {
    const id = e.target.id;

    if(!spaces[id]){
        spaces[id] = currentPlayer;
        e.target.innerText = currentPlayer;
        
        if(checkWinner() !== false){

            let winningCells = checkWinner();

            if (currentPlayer === playerX) {
                player1Wins++;
                document.getElementById('player1-wins').innerText = player1Wins;

                var jogador1Text = document.querySelector("#jogador1Text p");
                
                var nameWinner = document.querySelector("#titleText p");
                nameWinner.innerHTML = `${jogador1Text.innerHTML} venceu!`;
            } 
            else 
            if (currentPlayer === playerO) {
                player2Wins++;
                document.getElementById('player2-wins').innerText = player2Wins;

                var jogador2Text = document.querySelector("#jogador2Text p");
                
                var nameWinner = document.querySelector("#titleText p");
                nameWinner.innerHTML = `${jogador2Text.innerHTML} venceu!`;
            }

            cells.forEach(cell => cell.removeEventListener('click', cellClick));

            winningCells.forEach(cellIndex => {
                cells[cellIndex].style.backgroundColor = winnerColor;
            });
            
            setTimeout(() => {
                restartRodada();
            }, 3000);
            
            return;
        } else if (!spaces.includes(null)) {
            cells.forEach(cell => cell.removeEventListener('click', cellClick));
            var nameWinner = document.querySelector("#titleText p");
            nameWinner.innerHTML = `Empate!`;
            setTimeout(() => {
                restartRodada();
            }, 3000);
            return;
        }  
        currentPlayer = currentPlayer == playerX ? playerO : playerX;
    }
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

    cells.forEach(cell => {
        cell.innerText = '';
        cell.style.backgroundColor = '';
    });
    
    cells.forEach(cell => cell.addEventListener('click', cellClick));
    currentPlayer = playerX;
    
    let round = parseInt(document.querySelector("#rodadaCount p").innerText);
    round += 1;
    document.querySelector("#rodadaCount p").innerText = round;

    var nameWinner = document.querySelector("#titleText p");
    nameWinner.innerHTML = "Jogo da Velha";
}

startGame();