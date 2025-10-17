/*
  Arquivo JavaScript Sincronizado
  - As constantes que pegam elementos do HTML foram atualizadas para os IDs em português.
  - As manipulações de classes (classList.add/remove) foram atualizadas.
*/

// Namespace para exportar funções para o escopo global (HTML).
var escopoGlobal = this || self;
function definirGlobal(caminho, valor) {
    let partes = caminho.split(".");
    let objeto = escopoGlobal;
    for (var i = 0; i < partes.length - 1; i++) {
        let parte = partes[i];
        if (!(parte in objeto)) {
            objeto[parte] = {};
        }
        objeto = objeto[parte];
    }
    objeto[partes[partes.length - 1]] = valor;
}

// Elementos da Interface (com IDs em português)
const telaModoJogo = document.getElementById("telaModoJogo");
const telaModoControle = document.getElementById("telaModoControle");
const telaOpcoesJogo = document.getElementById("telaOpcoesJogo");
const telaDificuldade = document.getElementById("telaDificuldade");
const divInstrucoesUmJogador = document.getElementById("divInstrucoesUmJogador");
const divInstrucoesDoisJogadores = document.getElementById("divInstrucoesDoisJogadores");
const areaJogo = document.getElementById("areaJogo");
const telaJogo = document.getElementById("telaJogo");
const canvasJogo = document.getElementById("canvasJogo");
const pontuacaoJogador1Elem = document.getElementById("pontuacaoJogador1");
const pontuacaoJogador2Elem = document.getElementById("pontuacaoJogador2");
const divPausa = document.getElementById("divPausa");
const telaFimJogo = document.getElementById("telaFimJogo");
const estatisticasVitoriasElem = document.getElementById("estatisticasVitorias");
const contexto = canvasJogo.getContext("2d");

// Variáveis de Estado do Jogo
let somHabilitado = false;
let bola;
let estadoColisao = 0;
let alturaRaquete, unidadeTamanho;
let fatorVelocidadeIA;
let ajusteSensibilidade = 0;
let velocidadeRaquete;
let ehModoUmJogador = true;
let modoControle = 0;
let telaAtual = 0;
let nivelDificuldade = 1;
let velocidadeBaseBola = 7;
let pontosJogador1 = 0;
let pontosJogador2 = 0;
let raqueteJogador1, raqueteJogador2;
let jogoEmAndamento = false;
let jogoPausado = false;
let turnoSaqueJogador1 = true;
let pontuacaoParaVencer = 10;
let saqueLentoHabilitado = true;
let regraInicioAposPonto = 0;
const audioImpactoRaquete = new Audio("paddleBounce.mp3");
let posicaoTopoCanvas;
let vitoriasVidaInteira = 0;
let partidasVidaInteira = 0;
let timestampUltimoFrame = 0;
let teclasPressionadas = {};
let ehLocalhost = ("localhost" === window.location.hostname);

function registrarEvento(nomeEvento) {
    if (ehLocalhost) {
        console.log("Localhost - não registrando evento: " + nomeEvento);
    } else {
        if (typeof gtag !== "undefined" && "gtag" in window) {
            gtag("event", nomeEvento, {});
        }
    }
}

function Raquete(posX) {
    this.x = posX;
    this.y = canvasJogo.height / 2 - alturaRaquete / 2;
    this.largura = unidadeTamanho;
    this.altura = alturaRaquete;
    this.velocidadeY = 0;
}
Raquete.prototype.getCentroY = function() {
    return this.y + this.altura / 2;
};

function Bola() {
    this.velocidadeY = this.velocidadeX = 0;
    this.pontoMarcado = false;
    this.altura = this.largura = unidadeTamanho;
    this.x = canvasJogo.width / 2;
    this.y = canvasJogo.height / 2 - this.altura / 2 + (Math.floor(4 * Math.random()) + 1);
    this.direcaoDireita = true;
    definirDirecaoBola(this, 0, true);
}
Bola.prototype.getCentroY = function() {
    return this.y + this.altura / 2;
};

function definirDirecaoBola(bola, angulo, ehSaque) {
    let anguloRad = angulo * Math.PI / 4;
    let velocidade = ehSaque && saqueLentoHabilitado ? velocidadeBaseBola / 2 : velocidadeBaseBola;
    let direcao = bola.direcaoDireita ? 1 : -1;
    bola.velocidadeX = direcao * Math.cos(anguloRad) * velocidade * 100;
    bola.velocidadeY = Math.sin(anguloRad) * velocidade * 100;
}

function mostrarTelaPrincipal() {
    telaAtual = 0;
    audioImpactoRaquete.volume = 1;
    if (window.innerWidth < 650) areaJogo.style.width = .98 * window.innerWidth + "px";
    if (window.innerHeight < 480) {
        areaJogo.style.height = .98 * window.innerHeight + "px";
        unidadeTamanho = 15 * areaJogo.offsetHeight / 480;
    } else {
        unidadeTamanho = 15;
    }
    canvasJogo.width = areaJogo.offsetWidth;
    canvasJogo.height = areaJogo.offsetHeight;
    telaModoJogo.style.display = "flex";
    telaModoControle.style.display = "none";
    telaOpcoesJogo.style.display = "none";
    telaDificuldade.style.display = "none";
    telaJogo.style.display = "none";
    telaFimJogo.style.display = "none";
    divInstrucoesUmJogador.style.display = "none";
    divInstrucoesDoisJogadores.style.display = "none";

    let vitoriasSalvas = lerCookie("pgwins");
    vitoriasVidaInteira = 0;
    partidasVidaInteira = 0;
    if (vitoriasSalvas) {
        let partes = vitoriasSalvas.split("~");
        if (partes && partes.length == 2) {
            vitoriasVidaInteira = Number(partes[0]);
            partidasVidaInteira = Number(partes[1]);
        }
    } else {
        resetarEstatisticasGerais();
    }
    atualizarPlacarVitorias();

    let opcoesSalvas = lerCookie("pgopts");
    if (opcoesSalvas) {
        let partes = opcoesSalvas.split("~");
        if (partes && partes.length == 5) {
            somHabilitado = Number(partes[0]) != 0;
            ajusteSensibilidade = Number(partes[1]);
            pontuacaoParaVencer = Number(partes[2]);
            saqueLentoHabilitado = Number(partes[3]) != 0;
            regraInicioAposPonto = Number(partes[4]);
            atualizarIconeSom();
        }
    }
    if ("ontouchstart" in window || navigator.maxTouchPoints || navigator.msMaxTouchPoints > 0) {
        modoControle = .25;
        telaDificuldade.appendChild(document.getElementById("envolveBotaoSom"));
        definirModoDeJogo(true);
    }
}
definirGlobal("mostrarTelaPrincipal", mostrarTelaPrincipal);

function atualizarPlacarVitorias() {
    estatisticasVitoriasElem.innerHTML = vitoriasVidaInteira + " DE " + partidasVidaInteira;
}

definirGlobal("alternarSom", function() {
    somHabilitado = !somHabilitado;
    atualizarIconeSom();
});

function atualizarIconeSom() {
    const botaoSom = document.getElementById("botaoSom");
    botaoSom.classList.remove("somLigado", "somDesligado");
    botaoSom.classList.add(somHabilitado ? "somLigado" : "somDesligado");
    salvarOpcoesNosCookies();
}

function definirModoDeJogo(umJogador) {
    telaAtual = 1;
    ehModoUmJogador = umJogador;
    if (ehModoUmJogador) {
        document.getElementById("botaoDificuldadeFacil").innerHTML = '<span class="teclaBotao">(e)&nbsp;</span>Fácil';
        document.getElementById("botaoDificuldadeDificil").innerHTML = '<span class="teclaBotao">(h)&nbsp;</span>Difícil';
    } else {
        document.getElementById("botaoDificuldadeFacil").innerHTML = '<span class="teclaBotao">(s)&nbsp;</span>Lento';
        document.getElementById("botaoDificuldadeDificil").innerHTML = '<span class="teclaBotao">(f)&nbsp;</span>Rápido';
    }
    divInstrucoesUmJogador.style.display = ehModoUmJogador ? "block" : "none";
    divInstrucoesDoisJogadores.style.display = ehModoUmJogador ? "none" : "block";
    if (modoControle == .25) {
        definirModoControle(.25);
    } else if (ehModoUmJogador) {
        telaModoJogo.style.display = "none";
        telaJogo.style.display = "none";
        telaFimJogo.style.display = "none";
        telaModoControle.style.display = "flex";
        telaDificuldade.style.display = "none";
    } else {
        definirModoControle(0);
    }
}
definirGlobal("definirModoDeJogo", definirModoDeJogo);

function definirModoControle(modo) {
    telaAtual = 2;
    modoControle = modo;
    canvasJogo.removeEventListener("mousemove", gerenciarMovimentoMouse);
    canvasJogo.removeEventListener("touchstart", gerenciarToqueInicio);
    canvasJogo.removeEventListener("touchend", gerenciarToqueFim);
    if (modoControle == 1) canvasJogo.addEventListener("mousemove", gerenciarMovimentoMouse);
    else if (modoControle == .25) {
        canvasJogo.addEventListener("touchstart", gerenciarToqueInicio);
        canvasJogo.addEventListener("touchend", gerenciarToqueFim);
    }
    telaModoJogo.style.display = "none";
    telaModoControle.style.display = "none";
    telaDificuldade.style.display = "flex";
    telaJogo.style.display = "none";
    telaFimJogo.style.display = "none";
}
definirGlobal("definirModoControle", definirModoControle);

function definirDificuldade(nivel) {
    nivelDificuldade = nivel;
    iniciarJogo(200);
}
definirGlobal("definirDificuldade", definirDificuldade);

definirGlobal("iniciarJogoDaTelaFinal", function() {
    iniciarProximoJogo();
});

function iniciarJogo(delay) {
    telaAtual = 3;
    teclasPressionadas = {};
    jogoEmAndamento = true;
    telaModoJogo.style.display = "none";
    telaDificuldade.style.display = "none";
    telaJogo.style.display = "block";
    telaFimJogo.style.display = "none";
    canvasJogo.style.cursor = "none";
    pontosJogador1 = 0;
    pontuacaoJogador1Elem.innerHTML = pontosJogador1;
    pontosJogador2 = 0;
    pontuacaoJogador2Elem.innerHTML = pontosJogador2;
    turnoSaqueJogador1 = true;

    switch (nivelDificuldade) {
        case 0: velocidadeRaquete=ehModoUmJogador?4:3; velocidadeBaseBola=ehModoUmJogador?7:5; fatorVelocidadeIA=7; break;
        case 1: velocidadeRaquete=4; velocidadeBaseBola=7; fatorVelocidadeIA=9; break;
        case 2: velocidadeRaquete=ehModoUmJogador?4:5; velocidadeBaseBola=ehModoUmJogador?7:8; fatorVelocidadeIA=10.5; break;
        default: velocidadeRaquete=4; velocidadeBaseBola=7; fatorVelocidadeIA=9;
    }

    velocidadeRaquete = 100 * (velocidadeRaquete + ajusteSensibilidade);
    bola = new Bola();
    alturaRaquete = 5 * unidadeTamanho;
    raqueteJogador1 = new Raquete(2 * unidadeTamanho);
    raqueteJogador2 = new Raquete(canvasJogo.width - 3 * unidadeTamanho);
    posicaoTopoCanvas = canvasJogo.getBoundingClientRect().top;
    contexto.fillStyle = "lightgray";
    contexto.fillRect(0, 0, canvasJogo.width, unidadeTamanho);
    contexto.fillRect(0, canvasJogo.height - unidadeTamanho, canvasJogo.width, canvasJogo.height);
    desenharCanvas();
    setTimeout(() => {
        timestampUltimoFrame = Date.now();
        requestAnimationFrame(loopPrincipal);
    }, delay);
}
definirGlobal("iniciarJogo", iniciarJogo);

function encerrarJogo(codigoResultado) {
    telaAtual = 4;
    jogoPausado = false;
    divPausa.style.display = "none";
    jogoEmAndamento = false;
    if (ehModoUmJogador) {
        if (codigoResultado == 2) vitoriasVidaInteira += 1;
        if (codigoResultado != 0) partidasVidaInteira += 1;
        definirCookie("pgwins", vitoriasVidaInteira + "~" + partidasVidaInteira);
    }
    atualizarPlacarVitorias();
    telaFimJogo.style.display = "flex";
    canvasJogo.style.cursor = "pointer";
    telaFimJogo.classList.remove("posicaoVitoriaJogador1", "posicaoVitoriaJogador2");
    if (codigoResultado === 1) telaFimJogo.classList.add("posicaoVitoriaJogador1");
    else if (codigoResultado === 2) telaFimJogo.classList.add("posicaoVitoriaJogador2");
}

function desenharCanvas() {
    contexto.clearRect(0, unidadeTamanho, canvasJogo.width, canvasJogo.height - 2 * unidadeTamanho);
    contexto.fillStyle = "lightgray";
    contexto.fillRect(raqueteJogador1.x, raqueteJogador1.y, raqueteJogador1.largura, raqueteJogador1.altura);
    contexto.fillRect(raqueteJogador2.x, raqueteJogador2.y, raqueteJogador2.largura, raqueteJogador2.altura);
    if (jogoEmAndamento) contexto.fillRect(bola.x, bola.y, bola.largura, bola.altura);
    for (let i = unidadeTamanho; i < canvasJogo.height - unidadeTamanho; i += 2 * unidadeTamanho) {
        contexto.fillRect(canvasJogo.width / 2 - unidadeTamanho / 2, i, unidadeTamanho, unidadeTamanho);
    }
}

function loopPrincipal() {
    let agora = Date.now();
    let delta = (agora - timestampUltimoFrame) / 1E3;
    timestampUltimoFrame = agora;
    if (!jogoPausado) {
        if (modoControle == 0) {
            if (teclasPressionadas.ArrowUp) raqueteJogador2.velocidadeY = -velocidadeRaquete;
            else if (teclasPressionadas.ArrowDown) raqueteJogador2.velocidadeY = velocidadeRaquete;
            else raqueteJogador2.velocidadeY = 0;
            if (teclasPressionadas.s || teclasPressionadas.S) raqueteJogador1.velocidadeY = -velocidadeRaquete;
            else if (teclasPressionadas.x || teclasPressionadas.X) raqueteJogador1.velocidadeY = velocidadeRaquete;
            else raqueteJogador1.velocidadeY = 0;
        }
        if (ehModoUmJogador) raqueteJogador1.y += (bola.getCentroY() - raqueteJogador1.getCentroY()) * fatorVelocidadeIA * delta;
        else raqueteJogador1.y += raqueteJogador1.velocidadeY * delta;
        raqueteJogador2.y += raqueteJogador2.velocidadeY * delta;
        const limiteSuperior = unidadeTamanho;
        const limiteInferior = canvasJogo.height - unidadeTamanho - alturaRaquete;
        raqueteJogador1.y = Math.max(limiteSuperior, Math.min(raqueteJogador1.y, limiteInferior));
        raqueteJogador2.y = Math.max(limiteSuperior, Math.min(raqueteJogador2.y, limiteInferior));
        bola.x += bola.velocidadeX * delta;
        bola.y += bola.velocidadeY * delta;
        if (bola.y < unidadeTamanho) {
            bola.y = unidadeTamanho;
            bola.velocidadeY *= -1;
            if (somHabilitado) audioImpactoRaquete.play();
        } else if (bola.y + unidadeTamanho > canvasJogo.height - unidadeTamanho) {
            bola.y = canvasJogo.height - 2 * unidadeTamanho;
            bola.velocidadeY *= -1;
            if (somHabilitado) audioImpactoRaquete.play();
        }
        if ((bola.x < 0 || bola.x > canvasJogo.width) && !bola.pontoMarcado) {
            bola.pontoMarcado = true;
            let quemMarcou = 0;
            if (bola.x < 0) {
                pontosJogador2++;
                pontuacaoJogador2Elem.innerHTML = pontosJogador2;
                quemMarcou = 2;
            } else {
                pontosJogador1++;
                pontuacaoJogador1Elem.innerHTML = pontosJogador1;
                quemMarcou = 1;
            }
            if (pontosJogador1 >= pontuacaoParaVencer || pontosJogador2 >= pontuacaoParaVencer) {
                encerrarJogo(pontosJogador1 > pontosJogador2 ? 1 : 2);
                return;
            }
            setTimeout(() => {
                if (regraInicioAposPonto == 0) turnoSaqueJogador1 = !turnoSaqueJogador1;
                else if (regraInicioAposPonto == 1) turnoSaqueJogador1 = (quemMarcou == 2);
                else if (regraInicioAposPonto == 2) turnoSaqueJogador1 = (quemMarcou == 1);
                bola.direcaoDireita = turnoSaqueJogador1;
                bola.x = canvasJogo.width / 2;
                bola.y = Math.floor(Math.random() * (canvasJogo.height / 2 - bola.altura / 2)) + 100;
                definirDirecaoBola(bola, Math.floor(100 * Math.random()) / 100, true);
                bola.pontoMarcado = false;
            }, 450);
        }
        let raqueteAtiva = bola.direcaoDireita ? raqueteJogador2 : raqueteJogador1;
        if (bola.x < raqueteAtiva.x + raqueteAtiva.largura && bola.x + bola.largura > raqueteAtiva.x && bola.y < raqueteAtiva.y + raqueteAtiva.altura && bola.y + bola.altura > raqueteAtiva.y) {
            bola.direcaoDireita = !bola.direcaoDireita;
            let pontoImpactoRelativo = (bola.getCentroY() - raqueteAtiva.getCentroY()) / (raqueteAtiva.altura / 2);
            definirDirecaoBola(bola, pontoImpactoRelativo, false);
            if (somHabilitado) audioImpactoRaquete.play();
        }
        desenharCanvas();
        requestAnimationFrame(loopPrincipal);
    }
}

function gerenciarMovimentoMouse(evento) {
    if (modoControle == 1 && jogoEmAndamento) {
        let posY = evento.clientY - posicaoTopoCanvas;
        raqueteJogador2.y = Math.max(unidadeTamanho, Math.min(posY, canvasJogo.height - unidadeTamanho - alturaRaquete));
    }
}

document.addEventListener("keydown", function(evento) {
    const tecla = evento.key;
    if (telaAtual == 3 && modoControle == 0) {
        if (["ArrowUp", "ArrowDown", "s", "S", "x", "X"].includes(tecla)) {
            teclasPressionadas[tecla] = true;
            evento.preventDefault();
        }
    } else if (telaAtual == 0) {
        if (tecla === "1") definirModoDeJogo(true);
        else if (tecla === "2") definirModoDeJogo(false);
    } else if (telaAtual == 1) {
        if (tecla === "m" || tecla === "M") definirModoControle(1);
        else if (tecla === "k" || tecla === "K") definirModoControle(0);
    } else if (telaAtual == 2) {
        if (["e", "E", "s", "S"].includes(tecla)) definirDificuldade(0);
        else if (["m", "M"].includes(tecla)) definirDificuldade(1);
        else if (["f", "F", "h", "H"].includes(tecla)) definirDificuldade(2);
    } else if (telaAtual == 4) {
        if (tecla === "r" || tecla === "R") iniciarProximoJogo();
    }
});

document.addEventListener("keyup", function(evento) {
    const tecla = evento.key;
    if (tecla === "Escape") {
        if (jogoEmAndamento) encerrarJogo(0);
        mostrarTelaPrincipal();
    }
    if (jogoEmAndamento && (tecla == "p" || tecla == "P")) {
        jogoPausado = !jogoPausado;
        divPausa.style.display = jogoPausado ? "block" : "none";
        if (!jogoPausado) loopPrincipal();
    }
    if (modoControle == 0 && jogoEmAndamento) {
        if (["ArrowUp", "ArrowDown", "s", "S", "x", "X"].includes(tecla)) {
            teclasPressionadas[tecla] = false;
            evento.preventDefault();
        }
    }
});

function gerenciarToqueInicio(evento) {
    if (modoControle == .25 && jogoEmAndamento) {
        let toque = evento.touches[0] || evento.changedTouches[0];
        if (toque.clientY - posicaoTopoCanvas < raqueteJogador2.getCentroY()) raqueteJogador2.velocidadeY = -velocidadeRaquete;
        else raqueteJogador2.velocidadeY = velocidadeRaquete;
        evento.preventDefault();
    }
}

function gerenciarToqueFim(evento) {
    if (modoControle == .25 && jogoEmAndamento) {
        raqueteJogador2.velocidadeY = 0;
        evento.preventDefault();
    }
}

function abrirOpcoes() {
    telaAtual = 5;
    salvarOpcoesNosCookies();
    telaModoJogo.style.display = "none";
    telaOpcoesJogo.style.display = "block";
    const opcoes = {
        "pontuacao": pontuacaoParaVencer,
        "saque": saqueLentoHabilitado ? 1 : 0,
        "sensibilidade": ajusteSensibilidade,
        "inicio": regraInicioAposPonto
    };
    for (const grupo in opcoes) {
        document.querySelectorAll(`[id^='${grupo}']`).forEach(el => el.classList.remove("selecionado"));
        const elSelecionado = document.getElementById(`${grupo}${opcoes[grupo]}`);
        if(elSelecionado) elSelecionado.classList.add("selecionado");
    }
}
definirGlobal("abrirOpcoes", abrirOpcoes);

definirGlobal("definirPontuacaoParaVencer", function(pontos) {pontuacaoParaVencer = pontos; abrirOpcoes();});
definirGlobal("definirSaqueLento", function(habilitado) {saqueLentoHabilitado = habilitado; abrirOpcoes();});
definirGlobal("definirSensibilidadeTeclado", function(valor) {ajusteSensibilidade = valor; abrirOpcoes();});
definirGlobal("definirRegraDeInicio", function(regra) {regraInicioAposPonto = regra; abrirOpcoes();});

function salvarOpcoesNosCookies() {
    let valor = `${somHabilitado?1:0}~${ajusteSensibilidade}~${pontuacaoParaVencer}~${saqueLentoHabilitado?1:0}~${regraInicioAposPonto}`;
    definirCookie("pgopts", valor);
}

definirGlobal("resetarOpcoes", function() {
    ajusteSensibilidade = 0;
    pontuacaoParaVencer = 10;
    saqueLentoHabilitado = true;
    regraInicioAposPonto = 0;
    abrirOpcoes();
});

function resetarEstatisticasGerais() {
    vitoriasVidaInteira = 0;
    partidasVidaInteira = 0;
    definirCookie("pgwins", "0~0");
    atualizarPlacarVitorias();
}
definirGlobal("resetarEstatisticasGerais", resetarEstatisticasGerais);

function definirCookie(nome, valor) {
    let data = new Date();
    data.setDate(data.getDate() + 365);
    document.cookie = `${nome}=${valor}; expires=${data.toUTCString()};path=/`;
}

function lerCookie(nome) {
    let cookies = document.cookie;
    let indice = cookies.indexOf(` ${nome}=`);
    if (indice == -1) indice = cookies.indexOf(`${nome}=`);
    if (indice == -1) return null;
    else {
        indice = cookies.indexOf("=", indice) + 1;
        let fim = cookies.indexOf(";", indice);
        if (fim == -1) fim = cookies.length;
        return cookies.substring(indice, fim);
    }
}

function iniciarProximoJogo() {
    iniciarJogo(1000);
}