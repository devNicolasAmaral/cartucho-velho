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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }

        .container-fluid, .row {
            height: 100%;
        }

        .left-panel {
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('dev/IMG/Site/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            color: white;
            text-align: center;
        }
        
        .right-panel {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .logo-img {
            max-width: 150px;
            margin-bottom: 2rem;
        }

        .login-form {
            width: 100%;
            max-width: 400px;
        }

        .btn-pill {
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            font-weight: bold;
        }

        .btn-custom-login {
            background-color: #465efb;
            border-color: #465efb;
            color: white;
        }

        .btn-custom-login:hover {
            background-color: #3a4ed0ff;
            border-color: #3a4ed0ff;
            color: white;
        }
        
        .input-group .form-control {
            border-right: 0;
        }

        .input-group .input-group-text {
            background-color: white;
            border-left: 0;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(70, 94, 251, 0.25);
            border-color: #ced4da;
        }

        .cadastro {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-md-6 d-none d-md-flex left-panel">
                <div>
                    <h1 class="display-4"><?php echo NOME ?></h1>
                    <p class="lead">Crie, apresente e compartilhe conteúdo de forma inclusiva e impactante.</p>
                </div>
            </div>
            <div class="col-md-6 right-panel">
                <div class="login-form">
                    <div class="text-center mb-4">
                        <h2 class="mt-2">Bem-vindo(a) de volta!</h2>
                        <p class="text-muted">Acesse sua conta para continuar.</p>
                    </div>
    
                    <form action="dev/exec/login_exec.php" method="POST">
                        <?php
                            // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                            if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null) 
                            {
                                echo $_SESSION["msg"];
                                // Limpa a mensagem para evitar que seja exibida novamente
                                $_SESSION["msg"] = null;
                            }
                        ?>
                        <div class="mb-3">
                            <label for="user" class="form-label">Usuário ou Email</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="user" name="user" required>
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="lembrar" name="lembrar">
                                <label for="lembrar" class="form-check-label">Lembrar-me</label>
                            </div>
                            <a href="recuperar_senha.php" class="form-text mb-1">Esqueceu a senha?</a>
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
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="forgotPasswordForm">
                        <p>Digite seu e-mail e enviaremos um link para você criar uma nova senha.</p>
                        <div id="forgot-status-message"></div>
                        <div class="mb-3">
                            <label for="email_recuperacao" class="form-label">Seu e-mail</label>
                            <input type="email" class="form-control" id="email_recuperacao" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar link de recuperação</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>new window.VLibras.Widget('https://vlibras.gov.br/app');</script>
</body>
</html>
