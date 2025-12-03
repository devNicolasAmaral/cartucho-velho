<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "./dev/exec/config.php";
include DEV_PATH . "exec/conexao_banco.php";

$id_user = $_SESSION['ID_Usuario'] ?? null;
$user = null;

if ($id_user) {
    $sqlUser = "SELECT User, Foto_Perfil FROM USUARIOS WHERE ID_Usuario = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("i", $id_user);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $user = $resultUser->fetch_assoc();
}

// --- LÓGICA PARA CARREGAR DADOS DO JOGO ---
$gameData = null;
$id_jogo_atual = $_GET['id'] ?? null;
$slug_jogo = $_GET['jogo'] ?? null;

if ($id_jogo_atual) {
    // Busca pelo ID
    $sqlGame = "SELECT * FROM JOGOS WHERE ID_Jogo = ?";
    $stmtGame = $conn->prepare($sqlGame);
    $stmtGame->bind_param("i", $id_jogo_atual);
    $stmtGame->execute();
    $gameData = $stmtGame->get_result()->fetch_assoc();
}
elseif ($slug_jogo) {
    // Fallback: Se tiver apenas o slug na URL (ex: ?jogo=pong), tenta achar pelo nome ou script
    // Ajuste essa query conforme seu banco real se usar slug
    $sqlGame = "SELECT * FROM JOGOS WHERE Script LIKE ? LIMIT 1";
    $slugLike = "%" . $slug_jogo . "%";
    $stmtGame = $conn->prepare($sqlGame);
    $stmtGame->bind_param("s", $slugLike);
    $stmtGame->execute();
    $gameData = $stmtGame->get_result()->fetch_assoc();
    if ($gameData) $id_jogo_atual = $gameData['ID_Jogo'];
}

// Se não achou jogo, define dados padrão para não quebrar a tela
if (!$gameData) {
    $gameData = [
        'Nome' => 'Jogo Desconhecido',
        'Descricao' => 'Dados do jogo não encontrados.',
        'Curiosidades' => 'Sem curiosidades registradas.',
        'Script' => $slug_jogo ? $slug_jogo . '.js' : ''
    ];
}

// --- CARREGAR COMENTÁRIOS ---
$comentarios = [];
if ($id_jogo_atual) {
    $sqlComents = "SELECT C.*, U.User, U.Foto_Perfil 
                   FROM COMENTARIOS C 
                   JOIN USUARIOS U ON C.ID_Usuario = U.ID_Usuario 
                   WHERE C.ID_Jogo = ? 
                   ORDER BY C.Data_Comentario DESC";
    $stmtComents = $conn->prepare($sqlComents);
    $stmtComents->bind_param("i", $id_jogo_atual);
    $stmtComents->execute();
    $comentarios = $stmtComents->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Jogar: <?= htmlspecialchars($gameData['Nome']) ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Jersey+10&family=Workbench&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/index.css">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/modal-retro.css">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/toast-retro.css">
        <link rel="stylesheet" type="text/css" href="./jogos/game.css">
    </head>
    <body class="d-flex flex-column min-vh-100">
        <audio autoplay loop id="som" class="d-none">
            <source src="<?= DEV_URL ?>/MUSIC/bg-music.flac" type="audio/mpeg">
        </audio>
        <div class="content flex-grow-1">
            <div class="container-fluid p-2" style="margin-bottom: 50px;">
                <div class="d-flex justify-content-between align-items-center text-center" style="background: linear-gradient(90deg, #350BAB 0%, #5792E5 100%);">
                    <div class="m-2 d-flex justify-content-start text-secondary">
                        <a href="<?= BASE_URL ?>index.php">
                            <img src="<?= DEV_URL ?>IMG/Site/Logo/logoTexto.png" style="max-width: 180px;" alt="Logo Cartucho Velho">
                        </a>
                    </div>
                    
                    <div class="m-2">
                        <?php if ($id_user && $user) : // --- DROPDOWN DO USUÁRIO LOGADO --- ?>
                            <div class="dropdown p-1">
                                <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img class="img-fluid" src="<?php echo ($user['Foto_Perfil'] ?? false) ? "dev/" . $user['Foto_Perfil'] : PERFIL_PLACEHOLDER; ?>" alt="Foto do Usuário" width="50" height="50">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-retro">
                                    <li><span class="dropdown-item-text">Olá, <?php echo $_SESSION['Nome']; ?>!</span></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="openUploadModal()"><i class="bi bi-person-circle me-2"></i>Alterar Foto</a></li>
                                    <li><a class="dropdown-item" href="#" id="toggle-contrast-btn"><i class="bi bi-highlights me-2"></i></i>Ativar Contraste</a></li>
                                    <li><a class="dropdown-item" href="#" id="toggle-sound-btn"><i class="bi bi-volume-mute-fill me-2"></i>Desativar Som</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= DEV_URL ?>exec/logoff_exec.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                                </ul>
                            </div>
                        <?php else: // --- DROPDOWN DO VISITANTE --- ?>
                            <div class="dropdown p-1">
                                <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img class="img-fluid" src="<?php echo PERFIL_PLACEHOLDER?>" alt="Foto do Usuário" width="50" height="50">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-retro">
                                    <li><a class="dropdown-item" href="autenticacao.php?action=login"><i class="bi bi-box-arrow-in-right me-2"></i>Entrar</a></li>
                                    <li><a class="dropdown-item" href="autenticacao.php?action=cadastro"><i class="bi bi-person-plus-fill me-2"></i>Cadastrar</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" id="toggle-contrast-btn"><i class="bi bi-highlights me-2"></i></i>Ativar Contraste</a></li>
                                    <li><a class="dropdown-item" href="#" id="toggle-sound-btn"><i class="bi bi-volume-mute-fill me-2"></i>Desativar Som</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="container mb-5">
                
                <!-- ÁREA DO JOGO (Monitor) -->
                <div class="row justify-content-center aling-itens-center mb-4">
                    <div class="col-12 col-lg-6">
                        <div class="window-game">
                            <div class="title-bar">
                                <div class="title-bar-text" id="windowTitle"><?= htmlspecialchars($gameData['Nome']) ?>.exe</div>
                                <div class="title-bar-controls">
                                    <button aria-label="Minimize"></button>
                                    <button aria-label="Maximize"></button>
                                    <button aria-label="Close" onclick="window.location.href='index.php'"></button>
                                </div>
                            </div>
                            <div class="window-body">
                                <div id="game-root">
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <p class="text-white">Carregando Jogo...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ÁREA DE INFORMAÇÕES E COMENTÁRIOS -->
                <div class="row">
                    <!-- Coluna Esquerda: Descrição e Curiosidades -->
                    <div class="col-md-7 mb-3">
                        <div class="window-wrapper h-100">
                            <div class="title-bar">
                                <div class="title-bar-text">README.TXT - Informações</div>
                            </div>
                            <div class="window-body h-100">
                                <div class="retro-text-panel">
                                    <h4 class="retro-section-title">> Descrição do Jogo:</h4>
                                    <p>
                                        <?= nl2br(htmlspecialchars($gameData['Descricao'] ?? 'Sem descrição disponível.')) ?>
                                    </p>
                                    <hr style="border-top: 2px dashed #808080;">
                                    <h4 class="retro-section-title">> Curiosidades & Dicas:</h4>
                                    <p>
                                        <?= nl2br(htmlspecialchars($gameData['Curiosidades'] ?? 'Nenhuma curiosidade registrada para este cartucho.')) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coluna Direita: Comentários -->
                    <div class="col-md-5 mb-3">
                        <div class="window-wrapper h-100">
                            <div class="title-bar">
                                <div class="title-bar-text">BBS - Comentários</div>
                            </div>
                            <div class="window-body h-100 d-flex flex-column">
                                <!-- Lista de Comentários -->
                                <div class="comments-container flex-grow-1" id="lista-comentarios">
                                    <?php if ($id_jogo_atual && $comentarios && $comentarios->num_rows > 0): ?>
                                        <?php while($coment = $comentarios->fetch_assoc()): ?>
                                            <div class="comment-box">
                                                <div class="comment-avatar">
                                                    <img src="<?= ($coment['Foto_Perfil']) ? "dev/".$coment['Foto_Perfil'] : PERFIL_PLACEHOLDER ?>" alt="Avatar">
                                                </div>
                                                <div class="comment-content">
                                                    <div class="comment-header">
                                                        <span><?= htmlspecialchars($coment['User']) ?></span>
                                                        <span class="comment-date"><?= date('d/m/y H:i', strtotime($coment['Data_Comentario'])) ?></span>
                                                    </div>
                                                    <div><?= nl2br(htmlspecialchars($coment['Texto'])) ?></div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p class="text-center mt-3" style="font-family: 'VT323'; font-size: 18px;">Seja o primeiro a comentar neste cartucho!</p>
                                    <?php endif; ?>
                                </div>
                                <!-- Formulário de Envio -->
                                <div class="mt-2">
                                    <form action="<?= DEV_URL ?>exec/comentario_exec.php" method="POST" class="comment-form" id="form-comentario">
                                        <input type="hidden" name="id_jogo" value="<?= $id_jogo_atual ?>">
                                        
                                        <div class="mb-2">
                                            <textarea name="comentario" rows="3" placeholder="Deixe seu comentário aqui..." required></textarea>
                                        </div>
                                        
                                        <div class="d-flex justify-content-end">
                                            <?php if ($id_user): ?>
                                                <button type="submit">ENVIAR_MSG.EXE</button>
                                            <?php else: ?>
                                                <!-- O botão envia mesmo deslogado, o PHP redireciona e mostra o Toast -->
                                                <button type="submit" title="Você precisa estar logado">ENVIAR_MSG.EXE</button>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once DEV_PATH . 'views/footer.php'?>

        <!-- Toast -->
        <?php include_once DEV_PATH . 'views/toast.php'?>

        <?php if ($id_user): ?>
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-retro">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="uploadStatusMessage"></div>
                            <p class="text-center">
                                <img src="" alt="Imagem Atual" id="uploadPreview" class="img-thumbnail mb-3" style="max-width: 150px;">
                            </p>
                            <div class="mb-3">
                                <label for="arquivoUpload" class="form-label" id="uploadLabel"></label>
                                <input type="file" class="form-control" id="arquivoUpload" name="arquivo" accept="image/*" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-retro" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn-retro">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div vw class="enabled">
            <div vw-access-button class="active"></div>
            <div vw-plugin-wrapper>
                <div class="vw-plugin-top-wrapper"></div>
            </div>
        </div>
        <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
        <script>new window.VLibras.Widget('https://vlibras.gov.br/app');</script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="<?= DEV_URL ?>JS/toast.js"></script>
        <script>
            // --- NOVA LÓGICA PARA TOGGLE DE CONTRASTE E SOM ---
            document.addEventListener('DOMContentLoaded', function() {
                let isContrastOn = false;
                let isSoundOn = true; 
                let searchResults = [];
                const contrastBtn = document.getElementById('toggle-contrast-btn');
                const soundBtn = document.getElementById('toggle-sound-btn');
                const btnSom = document.getElementById('som');
                btnSom.volume = 0.2;

                const promise = btnSom.play();

                if (promise !== undefined) {
                    promise.then(_ => {
                        console.log('Autoplay iniciado com sucesso.');
                    }).catch(error => {
                        console.log('Autoplay bloqueado pelo navegador. Esperando interação do usuário.');
                        document.body.addEventListener('click', function() {
                            btnSom.play();
                        }, { once: true }); 
                    });
                }

                // Função para Contraste
                contrastBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    isContrastOn = !isContrastOn; 
                    
                    if (isContrastOn) {
                        document.body.classList.add('high-contrast');
                        this.innerHTML = '<i class="bi bi-highlights me-2"></i>Desativar Contraste';
                        this.innerHTML = '<i class="bi bi-highlights me-2"></i>Desativar Contraste';
                        mostrarToast('Modo Contraste Ativado', 'success');
                    } 
                    else {
                        document.body.classList.remove('high-contrast');
                        this.innerHTML = '<i class="bi bi-highlights me-2"></i>Ativar Contraste';
                        mostrarToast('Modo Contraste Desativado', 'success');
                    }
                });

                // Função para Som
                soundBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    isSoundOn = !isSoundOn; 

                    if (isSoundOn) {
                        console.log("Som ATIVADO");
                        btnSom.play();
                        this.innerHTML = '<i class="bi bi-volume-mute-fill me-2"></i>Desativar Som';
                        mostrarToast('Musica Ativada', 'success');
                    } 
                    else {
                        console.log("Som DESATIVADO");
                        btnSom.pause();
                        btnSom.currentTime = 0;
                        this.innerHTML = '<i class="bi bi-volume-up-fill me-2"></i>Ativar Som';
                        mostrarToast('Musica Desativada', 'success');
                    }
                });

                <?php
                    if (isset($_SESSION['msg']) && is_array($_SESSION['msg'])) {
                        $texto = addslashes($_SESSION['msg']['texto']);
                        $tipo = $_SESSION['msg']['tipo'];
                        
                        echo "mostrarToast('{$texto}', '{$tipo}');";

                        unset($_SESSION['msg']);
                    }
                ?>

                // SISTEMA DE CARREGAMENTO DE JOGOS (Game Loader)
            
                function carregarScriptDoJogo() {
                    const nomeJogo = "<?= htmlspecialchars($gameData['Script'] ?? '') ?>";

                    if (!nomeJogo) return; // Se não tiver jogo, não faz nada

                    // 2. Cria a tag script dinamicamente
                    const script = document.createElement('script');
                    script.src = `./jogos/${nomeJogo}.js`; // Assume que o arquivo chama pong.js
                    
                    script.onload = function() {
                        console.log(`Jogo ${nomeJogo} carregado com sucesso.`);
                        // O arquivo JS do jogo deve chamar automaticamente sua função de início
                    };

                    script.onerror = function() {
                        document.getElementById('game-root').innerHTML = 
                            "<p style='color:red; text-align:center'>Erro ao carregar o jogo: " + nomeJogo + "</p>";
                    };

                    document.body.appendChild(script);
                }

                // Inicia o processo
                window.onload = carregarScriptDoJogo;

                const commentForm = document.getElementById('form-comentario');
                const commentsList = document.getElementById('lista-comentarios');

                if (commentForm) {
                    commentForm.addEventListener('submit', function(e) {
                        e.preventDefault(); // 1. Impede a página de recarregar

                        const formData = new FormData(this);
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalBtnText = submitBtn.innerText;

                        // Efeito visual de carregamento retro
                        submitBtn.disabled = true;
                        submitBtn.innerText = "ENVIANDO...";

                        fetch(this.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            // 2. Verifica se o backend pediu redirecionamento (caso não logado)
                            if (data.redirect) {
                                window.location.href = 'autenticacao.php?action=login';
                                return;
                            }

                            if (data.success) {
                                // 3. Injeta o HTML do novo comentário no TOPO da lista (afterbegin)
                                if (data.html) 
                                    commentsList.insertAdjacentHTML('afterbegin', data.html);

                                // Limpa o campo de texto
                                commentForm.querySelector('textarea').value = '';
                                
                                // Remove a mensagem de "Seja o primeiro a comentar" se ela existir
                                const emptyMsg = commentsList.querySelector('p.text-center');
                                if (emptyMsg) emptyMsg.remove();

                                mostrarToast(data.message, 'success');
                            } 
                            else 
                                mostrarToast(data.message || 'Erro ao comentar', 'error');
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            mostrarToast('Erro de comunicação com o servidor.', 'error');
                        })
                        .finally(() => {
                            // Restaura o botão
                            submitBtn.disabled = false;
                            submitBtn.innerText = originalBtnText;
                        });
                    });
                }
            });
            
        </script>
        <?php if ($id_user): ?>
        <script>
            // --- LÓGICA DO MODAL DE UPLOAD ---
            const uploadModalEl = document.getElementById('uploadModal');
            const uploadModal = new bootstrap.Modal(uploadModalEl);
            
            const uploadModalLabel = document.getElementById('uploadModalLabel');
            const uploadPreview = document.getElementById('uploadPreview');
            const uploadTypeInput = document.getElementById('uploadType');
            const uploadLabel = document.getElementById('uploadLabel');
            const uploadStatusMessage = document.getElementById('uploadStatusMessage');

            function openUploadModal() {
                uploadStatusMessage.innerHTML = ''; 
                
                uploadModalLabel.textContent = 'Alterar Foto de Perfil';
                uploadLabel.textContent = 'Escolha uma nova foto de perfil (quadrada, de preferência).';
                uploadPreview.src = document.querySelector('.dropdown-toggle img').src;
                
                uploadModal.show();
            }

            document.getElementById('uploadForm').addEventListener('submit', async function(event) {
                event.preventDefault(); 

                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';

                try {
                    const response = await fetch('<?= DEV_URL ?>exec/ajax_upload_perfil.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (result.success) {
                        uploadStatusMessage.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                        if (formData.get('tipo') === 'foto')
                            document.querySelector('.dropdown-toggle img').src = result.newPath;
                        
                        setTimeout(() => uploadModal.hide(), 1500);
                        mostrarToast('Foto Alterada Com Sucesso!', 'success');
                    } 
                    else
                        uploadStatusMessage.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
                } 
                catch (error) {
                    uploadStatusMessage.innerHTML = `<div class="alert alert-danger">Erro de conexão. Tente novamente.</div>`;
                    console.log(error);
                } 
                finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Salvar Alterações';
                }
            });
        </script>
        <?php endif; ?>
    </body>
</html>