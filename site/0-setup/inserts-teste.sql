INSERT INTO `JOGOS` (`Nome`, `Descrição`, `Caminho`, `Script`) VALUES
('PONG', 'O clássico arcade de tênis de mesa. Rebata a bola com sua raquete e marque pontos contra o adversário.', 'dev/IMG/Site/Cartuchos/cartuchoBasiquinho.png', 'pong'),
('Jogo da Velha', 'O tradicional jogo de estratégia. Alinhe três X ou O em uma grade 3x3 para vencer.', 'dev/IMG/Site/Cartuchos/cartuchoLaranjaLaranja.png', 'velha'),
('Campo Minado', 'Teste sua lógica e sorte. Encontre todos os quadrados vazios sem detonar nenhuma mina escondida.', 'dev/IMG/Site/Cartuchos/cartuchoAzulGoiaba.png', 'campominado'),
('Snake', 'Controle a cobra e coma as maçãs para crescer. Cuidado para não bater nas paredes ou em si mesma!', 'dev/IMG/Site/Cartuchos/cartuchoVermelhin.png', 'snake');

-- -----------------------------------------------------
-- Atualizando PONG
-- -----------------------------------------------------
UPDATE JOGOS 
SET 
    Descrição = 'O avô dos videogames! Lançado originalmente pela Atari em 1972, PONG é uma simulação minimalista de tênis de mesa. O objetivo é simples: rebater a bola para o lado do adversário e evitar que ela passe por você. Apesar da simplicidade, a física da bola e a velocidade crescente tornam cada partida um duelo de reflexos intenso.',
    Curiosidades = 'CURIOSIDADE:\nO protótipo original do PONG foi construído em uma televisão Hitachi preto e branco comprada em uma loja local, colocada dentro de uma caixa de madeira improvisada. O sucesso foi tanto que a máquina quebrou no primeiro dia porque o compartimento de moedas ficou cheio demais!\n\nDICA DO MESTRE:\nNão jogue apenas defensivamente! Se você acertar a bola com a "quina" da sua raquete enquanto se move, consegue criar um ângulo agudo difícil de defender. Use isso para tirar o adversário da zona de conforto.'
WHERE Nome = 'PONG';

-- -----------------------------------------------------
-- Atualizando Jogo da Velha
-- -----------------------------------------------------
UPDATE JOGOS 
SET 
    Descrição = 'Também conhecido como Tic-Tac-Toe, este é um jogo de estratégia clássico que atravessa gerações. Jogado em um grid 3x3, o desafio é alinhar três símbolos iguais (na horizontal, vertical ou diagonal) antes do seu oponente. Parece simples, mas contra um jogador experiente (ou nossa CPU), cada movimento conta.',
    Curiosidades = 'CURIOSIDADE:\nJogos semelhantes ao da velha foram encontrados marcados em telhas romanas datadas de séculos antes de Cristo. É um dos jogos mais antigos da humanidade ainda praticados hoje!\n\nDICA ESTRATÉGICA:\nSe você começar jogando, pegue o centro! O centro oferece o maior número de combinações possíveis para vitória (4 caminhos). Se o oponente começar e pegar o centro, pegue uma das quinas (cantos) para aumentar suas chances de forçar um empate.'
WHERE Nome = 'Jogo da Velha';

-- -----------------------------------------------------
-- Atualizando Campo Minado
-- -----------------------------------------------------
UPDATE JOGOS 
SET 
    Descrição = 'Um verdadeiro teste de lógica e sangue frio. O tabuleiro esconde diversas minas terrestres; seu objetivo é revelar todas as casas vazias sem detonar nenhuma bomba. Os números revelados indicam quantas minas existem nas 8 casas adjacentes. Use a dedução matemática para plantar bandeiras e limpar a área.',
    Curiosidades = 'CURIOSIDADE:\nO Campo Minado foi incluído no Windows 3.1 não apenas como diversão, mas para ensinar os usuários a usar o mouse com precisão e entender a diferença entre o "clique esquerdo" e o "clique direito".\n\nDICA DE SOBREVIVÊNCIA:\nProcure pelo padrão "1-2-1". Se você vir esses números alinhados em uma parede reta de blocos não revelados, as minas estarão sempre ao lado dos números "1", e o "2" estará livre. Comece sempre pelos cantos para abrir mais espaço!'
WHERE Nome = 'Campo Minado';

-- -----------------------------------------------------
-- Atualizando Snake
-- -----------------------------------------------------
UPDATE JOGOS 
SET 
    Descrição = 'Assuma o controle da serpente faminta neste clássico absoluto dos anos 90. Colete os pixels de comida para crescer, mas cuidado: quanto maior a cobra, menor o espaço de manobra. O jogo termina se você colidir com as paredes ou com o próprio corpo. Um teste definitivo de planejamento espacial e reflexos.',
    Curiosidades = 'CURIOSIDADE:\nEmbora tenha explodido em popularidade com os celulares Nokia em 1997, o conceito original vem de um arcade de 1976 chamado "Blockade". Naquela época, era apenas em preto e branco e verde!\n\nDICA PRO:\nNão ande aleatoriamente! Tente manter a cobra em um padrão de "zigue-zague" (como uma escada) preenchendo o espaço de forma compacta. Evite dar voltas grandes e deixar "bolsões" de espaço vazio no meio da tela que você não conseguirá acessar depois.'
WHERE Nome = 'Snake';