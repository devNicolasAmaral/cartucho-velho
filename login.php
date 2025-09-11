<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "./dev/exec/config.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo NOME?> - Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/login.css">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/toast-retro.css">
    </head>
    <body>
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-md-6 d-none d-md-flex left-panel">
                    <div>
                        <h5 class="text-danger">Faça o login no</h5>
                        <img src="dev/IMG/Site/Logo/LogoTexto.png" alt="Logo do site, CARTUCHO VELHO" style="max-width: 400px;" class="img-fluid m-3">
                        <h5 class="text-danger">e dê um <strong class="text-success">START</strong> nessa aventura retrô!</h5>
                    </div>
                </div>
                <div class="col-md-6 right-panel d-flex flex-column">
                    
                    <div class="p-1 banner">
                        <div class="d-flex justify-content-between align-items-center text-center" style="background: linear-gradient(90deg, #350BAB 0%, #5792E5 100%);">
                            <div class="m-1 d-flex justify-content-start"><a href="login.php" class="text-white"><img src="dev/IMG/Site/Logo/logo.png" style="max-width: 35px;" alt="Logo Cartucho Velho"> Login</a></div>
                            <div class="fechar m-1 d-flex justify-content-end"><a href="index.php" class="text-black"><i class="bi bi-x-lg p-1"></i></a></div>
                        </div>
                    </div>

                    <div class="flex-grow-1 d-flex justify-content-center align-items-center">
                        
                        <div class="login-form">
                            <div class="p-3">
                                <div class="text-center mb-4">
                                    <img class="img-fluid m-2" src="<?php echo PERFIL_PLACEHOLDER?>" alt="Foto do Usuário" width="180" height="180">
                                </div>
                
                                <form action="dev/exec/login_exec.php" method="POST">
                                    <div class="mb-3">
                                        <label for="user" class="form-label">Usuário</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="user" name="user" value="admin" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Senha</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" value="••••••" required>
                                        </div>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-custom-login btn-pill mt-2">Entrar</button>
                                    </div>
                                </form>
                                <div class="text-center mt-4">
                                    <p class="text-muted">Não tem uma conta? <a class="cadastro" href="cadastro.php">Cadastra-se</a></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

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
