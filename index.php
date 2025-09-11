<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "./dev/exec/config.php";
include DEV_PATH . "exec/conexao_banco.php";

$id_user = $_SESSION['ID_Usuario'] ?? null;
$user = null;

if ($id_user) {
    $sqlUser = "SELECT Nome, Foto_Perfil FROM USUARIOS WHERE ID_Usuario = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("i", $id_user);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $user = $resultUser->fetch_assoc();
}

$sql = "SELECT * FROM JOGOS";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$jogos = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cartucho Velho</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/index.css">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/modal-retro.css">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/toast-retro.css">
    </head>
    <body class="d-flex flex-column min-vh-100">
        <div class="content flex-grow-1">
            <div class="container-fluid p-2">
                <div class="d-flex justify-content-between align-items-center text-center" style="background: linear-gradient(90deg, #350BAB 0%, #5792E5 100%);">
                    <div class="m-1 d-flex justify-content-start text-secondary"><a href="index.php"><img src="dev/IMG/Site/Logo/logoTexto.png" style="max-width: 180px;" alt="Logo Cartucho Velho"></a></div>
                    
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
                                    <li><a class="dropdown-item" href="login.php"><i class="bi bi-box-arrow-in-right me-2"></i>Entrar</a></li>
                                    <li><a class="dropdown-item" href="cadastro.php"><i class="bi bi-person-plus-fill me-2"></i>Cadastrar</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" id="toggle-contrast-btn"><i class="bi bi-highlights me-2"></i></i>Ativar Contraste</a></li>
                                    <li><a class="dropdown-item" href="#" id="toggle-sound-btn"><i class="bi bi-volume-mute-fill me-2"></i>Desativar Som</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="container mt-3 p-4">
                <div class="text-center mb-3">
                    <h2 class="text-white">Todos os jogos</h2>
                </div>

                <div class="row justify-content-center mb-4">
                    <div class="col-12 col-md-6">
                        <input class="search-bar-retro" list="datalistOptions" id="exampleDataList" placeholder="Digite para pesquisar seu jogo...">
                        <datalist id="datalistOptions">
                            <option value="Mario">
                            <option value="Snake">
                            <option value="Bomberman">
                            <option value="Circus Charlie">
                            <option value="Chicago">
                        </datalist>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="content">
                        <div class="row row-cols-2 row-cols-md-4 g-2">
                            <div class="col">
                                <a href="">
                                    <div class="cartucho" style="background-image: url('dev/IMG/Site/Cartuchos/cartuchoAzulGoiaba.png');"></div>
                                </a>
                            </div>
                            <div class="col">
                                <a href="">
                                    <div class="cartucho" style="background-image: url('dev/IMG/Site/Cartuchos/cartuchoBasiquinho.png');"></div>
                                </a>
                            </div>
                            <div class="col">
                                <a href="">
                                    <div class="cartucho" style="background-image: url('dev/IMG/Site/Cartuchos/cartuchoLaranjaLaranja.png');"></div>
                                </a>
                            </div>
                            <div class="col">
                                <a href="">
                                    <div class="cartucho" style="background-image: url('dev/IMG/Site/Cartuchos/cartuchoVermelhin.png');"></div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once DEV_PATH . 'views/footer.php'?>

        <?php if ($id_user): // ATUALIZAR PARA SE ENCAIXAR NO NOSSO PADRÃO DE DESING?>
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

        <!-- Toast -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="liveToast" class="toast toast-retro" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                <strong class="me-auto" id="toastTitulo">Notificação</strong>
                <button type="button" class="btn-retro" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" id="toastCorpo">
                </div>
            </div>
        </div>
        
        <div vw class="enabled">
            <div vw-access-button class="active"></div>
            <div vw-plugin-wrapper>
                <div class="vw-plugin-top-wrapper"></div>
            </div>
        </div>
        <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
        <script>new window.VLibras.Widget('https://vlibras.gov.br/app');</script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // --- NOVA LÓGICA PARA TOGGLE DE CONTRASTE E SOM ---
            document.addEventListener('DOMContentLoaded', function() {
                const contrastBtn = document.getElementById('toggle-contrast-btn');
                const soundBtn = document.getElementById('toggle-sound-btn');
                
                let isContrastOn = false;
                let isSoundOn = true; 

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
                        this.innerHTML = '<i class="bi bi-volume-mute-fill me-2"></i>Desativar Som';
                        mostrarToast('Musica Ativada', 'success');
                    } 
                    else {
                        console.log("Som DESATIVADO");
                        this.innerHTML = '<i class="bi bi-volume-up-fill me-2"></i>Ativar Som';
                        mostrarToast('Musica Desativada', 'success');
                    }
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
        <script src="<?= DEV_URL ?>JS/toast.js"></script>
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