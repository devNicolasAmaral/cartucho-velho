<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "./dev/exec/config.php";
include DEV_PATH . "exec/conexao_banco.php";

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
        <style>
            body {
                background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('dev/IMG/Site/background.png');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                
            }

            .cart-img {
                max-height: 160px;
                max-width: 160px;
                align-items: center;
            }

            .cartucho {
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center center;
                height: 260px;
                width: 240px;
                justify-content: center;
                align-items: center;
                transition: all 0.3s ease;
            }

            .cartucho:hover{
                transform: scale(1.05) translateY(-9px);
            }

            .btn-menu {
                width: 45px;
                height: 45px;
                border: none;
            }
        </style>
    </head>
    <body class="d-flex flex-column min-vh-100">
        <div class="content flex-grow-1">
            <!-- Banner -->
            <div class="container-fluid p-2" style="background: linear-gradient(90deg, #C2D8E5 0%, #BAE0E6 100%); border: 4px outset #d6e5eeff;">
                <div class="d-flex justify-content-between align-items-center text-center" style="background: linear-gradient(90deg, #350BAB 0%, #5792E5 100%);">
                    <div class="m-1 d-flex justify-content-start text-secondary"><a href="index.php"><img src="dev/IMG/Site/Logo/logoTexto.png" style="max-width: 180px;" alt="Logo Cartucho Velho"></a></div>
                    <div class="m-1 align-items-center">
                        <button class="btn-menu" style="background-color: #C2D8E5; border: 3px outset #d6e5eeff;"><img src="dev/IMG/Site/Icones/lupa.png" style="max-width: 30px;" alt="Lupa/Pesquisar"></button>
                        <button class="btn-menu" style="background-color: #C2D8E5; border: 3px outset #d6e5eeff;"><img src="dev/IMG/Site/Icones/shuffle.png" style="max-width: 30px;" alt="Jogo Aleatório"></button>
                        <button class="btn-menu" style="background-color: #C2D8E5; border: 3px outset #d6e5eeff;"><img src="dev/IMG/Site/Icones/som.png" style="max-width: 30px;" alt="Som"></button>
                    </div>
                </div>
            </div>

            <div class="container mt-3 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="text-white">Todos os jogos</h2>
                    <div class="datalist">
                        <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Digite seu jogo">
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

        <div vw class="enabled">
            <div vw-access-button class="active"></div>
            <div vw-plugin-wrapper>
                <div class="vw-plugin-top-wrapper"></div>
            </div>
        </div>
        <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
        <script>new window.VLibras.Widget('https://vlibras.gov.br/app');</script>

        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        
        <!-- Footer -->
        <?php include_once DEV_PATH . 'views/footer.php'?>

        <script>
            
        </script>
    </body>
</html>
