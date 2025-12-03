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
        <title><?php echo NOME ?> - Autenticação</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/autenticacao.css">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/toast-retro.css">
    </head>
    <body>
        <div class="container" id="container">
            <div class="form-container sign-up-container">
                <div class="p-1 banner">
                    <div class="bdentro d-flex justify-content-between align-items-center text-center" style="background: linear-gradient(90deg, #350BAB 0%, #5792E5 100%);">
                        <div class="m-1 d-flex justify-content-start"><a href="autenticacao.php" class="text-white"><img src="dev/IMG/Site/Logo/logo.png" style="max-width: 35px;" alt="Logo Cartucho Velho"> Crie sua Conta</a></div>
                        <div class="fechar m-1 d-flex justify-content-end"><a href="index.php" class="text-black"><i class="bi bi-x-lg p-1"></i></a></div>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex justify-content-center align-items-center">
                    <div class="p-0">
                        <div>
                            <div class="text-center mb-3">
                                <img class="img-fluid" src="<?php echo PERFIL_PLACEHOLDER?>" alt="Foto do Usuário" width="150" height="150">
                            </div>
                        </div>
                        <form action="dev/exec/cadastro_exec.php" method="POST">
                            <div class="mb-1 w-100">
                                <label for="email-cadastro" class="form-label">Email</label>
                                <div class="bfora">
                                    <input type="email" class="form-control" id="email-cadastro" name="email-cadastro" required>
                                </div>
                            </div>
                            <div class="mb-1 w-100">
                                <label for="user-cadastro" class="form-label">Usuário</label>
                                <div class="bfora">
                                    <input type="text" class="form-control" id="user-cadastro" name="user-cadastro" required>
                                </div>
                            </div>
                            <div class="mb-2 w-100">
                                <label for="password-cadastro" class="form-label">Senha</label>
                                <div class="bfora">
                                    <input type="password" class="form-control" id="password-cadastro" name="password-cadastro" required>
                                </div>
                            </div>
                            <div class="div-btn mt-3">
                                <button type="submit" class="btn btn-custom btn-pill mt-2">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="form-container sign-in-container">
                <div class="p-1 banner">
                    <div class="bdentro d-flex justify-content-between align-items-center text-center" style="background: linear-gradient(90deg, #350BAB 0%, #5792E5 100%);">
                        <div class="m-1 d-flex justify-content-start"><a href="autenticacao.php" class="text-white"><img src="dev/IMG/Site/Logo/logo.png" style="max-width: 35px;" alt="Logo Cartucho Velho"> Bem vindo(a) de Volta!</a></div>
                        <div class="fechar m-1 d-flex justify-content-end"><a href="index.php" class="text-black"><i class="bi bi-x-lg p-1"></i></a></div>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex justify-content-center align-items-center">
                    <div class="p-0">
                        <div>
                            <div class="text-center mb-3">
                                <img class="img-fluid" src="<?php echo PERFIL_PLACEHOLDER?>" alt="Foto do Usuário" width="150" height="150">
                            </div>
                        </div>
                        <form action="dev/exec/login_exec.php" method="POST">
                            <div class="mb-1 w-100">
                                <label for="user-login" class="form-label">Usuário</label>
                                <div class="bfora">
                                    <input type="text" class="form-control" id="user-login" name="user-login" required>
                                </div>
                            </div>
                            <div class="mb-2 w-100">
                                <label for="password-login" class="form-label">Senha</label>
                                <div class="bfora">
                                    <input type="password" class="form-control" id="password-login" name="password-login" required>
                                </div>
                            </div>
                            <div class="div-btn mt-3">
                                <button type="submit" class="btn btn-custom btn-pill mt-2">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <a href="index.php"><img src="dev/IMG/Site/Logo/logoTexto.png" alt="CARTUCHO VELHO" style="max-width: 400px;" class="img-fluid m-3"></a>
                        <h1 class="text-danger mt-2">Já tem uma conta?</h1>
                        <p class="text-warning">Faça o login para acessar a galeria de jogos completa!</p>
                        <div class="popup">
                            <div class="popup-banner" style="background: linear-gradient(90deg, #350BAB 0%, #5792E5 100%);">
                                <div class="m-1 d-flex justify-content-start"><p class="text-warning"><i class="bi bi-exclamation-square-fill"></i></p> System message</div>
                                <div class="fechar m-1 d-flex justify-content-end"><p class="text-black"><i class="bi bi-x-lg p-1"></i></p></div>
                            </div>
                            <div class="flex-grow-1 popup-btn-div">
                                <button class="popup-btn" id="signIn">Entrar</button>
                            </div>
                        </div>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <a href="index.php"><img src="dev/IMG/Site/Logo/logoTexto.png" alt="CARTUCHO VELHO" style="max-width: 400px;" class="img-fluid m-3"></a>
                        <h1 class="text-danger mt-2">Ainda não é membro?</h1>
                        <p class="text-warning">Cadastre-se e comece a explorar o universo dos jogos clássicos.</p>
                        <div class="popup">
                            <div class="popup-banner" style="background: linear-gradient(90deg, #350BAB 0%, #5792E5 100%);">
                                <div class="m-1"><p class="text-warning"><i class="bi bi-exclamation-square-fill"></i></p> System message</div>
                                <div class="fechar m-1 d-flex justify-content-end"><p class="text-black"><i class="bi bi-x-lg p-1 text-center"></i></p></div>
                            </div>
                            <div class="flex-grow-1 popup-btn-div">
                                <button class="popup-btn" id="signUp">Cadastre-se</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <?php include_once DEV_PATH . 'views/toast.php'?>

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
            const signUpButton = document.getElementById('signUp');
            const signInButton = document.getElementById('signIn');
            const container = document.getElementById('container');

            signUpButton.addEventListener('click', () => {
                container.classList.add("right-panel-active");
            });

            signInButton.addEventListener('click', () => {
                container.classList.remove("right-panel-active");
            });

            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const action = urlParams.get('action'); 

                if (action === 'cadastro') 
                    container.classList.add("right-panel-active");
                else 
                    container.classList.remove("right-panel-active");
            });

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