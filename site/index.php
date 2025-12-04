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

$jogos = $conn->query("SELECT ID_Jogo, Nome, Descrição, Caminho, Script FROM JOGOS ORDER BY Nome");
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cartucho Velho</title>
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
            <source src="./dev/MUSIC/bg-music.flac" type="audio/mpeg">
        </audio>
        <div class="content flex-grow-1">
            <div class="container-fluid p-2">
                <div class="d-flex justify-content-between align-items-center text-center" style="background: linear-gradient(90deg, #350BAB 0%, #5792E5 100%);">
                    <div class="m-2 d-flex justify-content-start text-secondary"><a href="index.php"><img src="dev/IMG/Site/Logo/logoTexto.png" style="max-width: 180px;" alt="Logo Cartucho Velho"></a></div>
                    
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
                                    <li><a class="dropdown-item" href="dev/exec/logoff_exec.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
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

            <div class="container mt-2 p-4">
                <div class="text-center mb-3">
                    <h4 class="text-white title-games" style="text-shadow: 4px 4px #000;">GALERIA DE JOGOS</h4>
                </div>
                <div class="row justify-content-center mb-3">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="window-wrapper">
                            <div class="title-bar">
                                <div class="title-bar-text">LIBRARY_SEARCH.EXE</div>
                            </div>
                            <div class="window-body">
                                <p style="margin-bottom: 5px; font-size: 14px; font-family: 'Pixelify Sans', sans-serif;">Localizar Cartucho:</p>
                                <div class="input-group">
                                    <input type="text" id="busca_jogo" class="form-control search-bar-retro" placeholder="Digite o nome do jogo..." aria-describedby="basic-addon1" autofocus>
                                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="content">
                        <div id="search-results-container" class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 justify-content-center">
                            <?php while ($j = $jogos->fetch_assoc()): ?>
                                <div class="col d-flex justify-content-center">
                                    <a href="./jogo.php?jogo=<?= $j['Script'] ?>" title="<?= $j['Nome'] ?>">
                                        <div class="cartucho" style="background-image: url(<?= htmlspecialchars($j['Caminho']) ?>);">
                                            <div class="cartucho-overlay">JOGAR</div>
                                        </div>
                                    </a>
                                </div>
                            <?php endwhile; ?>
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
                const buscaJogoInput = document.getElementById('busca_jogo');
                const searchResultsContainer = document.getElementById('search-results-container');
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

                function renderSearchResults(results) {
                    searchResultsContainer.innerHTML = '';

                    if (!Array.isArray(results) || results.length === 0) {
                        searchResultsContainer.innerHTML = `
                            <div class="col-12 d-flex justify-content-center">
                                <div class="window-wrapper" style="max-width: 400px; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                                    <div class="title-bar" style="background: linear-gradient(90deg, #800000, #ff0000);">
                                        <div class="title-bar-text">ERROR_404.EXE</div>
                                        <div class="title-bar-controls">
                                            <button aria-label="Close">X</button>
                                        </div>
                                    </div>
                                    <div class="window-body text-center p-4">
                                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 32px; color: #808080;"></i>
                                        <p style="font-family: 'VT323'; font-size: 22px; margin-top: 10px; margin-bottom: 0;">
                                            Nenhum cartucho encontrado com esse nome.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                        return;
                    }

                    results.forEach(item => {
                        const col = document.createElement('div');
                        col.className = 'col d-flex justify-content-center';
                        col.innerHTML = `
                            <a href="jogo.php?id=${item.ID_Jogo}" title="${item.Nome}">
                                <div class="cartucho" style="background-image: url('${item.Caminho}');">
                                    <div class="cartucho-overlay">JOGAR</div>
                                </div>
                            </a>
                        `;
                        searchResultsContainer.appendChild(col);
                    });
                }

                buscaJogoInput.addEventListener('keyup', function() {
                    const query = this.value;
                    fetch(`dev/exec/busca_jogos.php?jogo=${query}`)
                        .then(response => response.json())
                        .then(data => renderSearchResults(data))
                        .catch(error => console.error('Erro ao buscar jogos:', error));
                });
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
                    const response = await fetch('dev/exec/ajax_upload_perfil.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (result.success) {
                        uploadStatusMessage.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                        setTimeout(() => {
                            uploadModal.hide();
                            setTimeout(() => {
                                location.reload();
                            }, 300);
                        }, 1500);
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
        <script>
            <?php
            if (isset($_SESSION['msg']) && is_array($_SESSION['msg'])) {
                $texto = addslashes($_SESSION['msg']['texto']);
                $tipo = $_SESSION['msg']['tipo'];
                
                echo "mostrarToast('{$texto}', '{$tipo}');";

                unset($_SESSION['msg']);
            }
            ?>
        </script>
    </body>
</html>