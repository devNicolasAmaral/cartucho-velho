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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            img {
                max-height: 160px;
                max-width: 160px;
                align-items: center;
            }

            .card {
                background-image: url('dev/IMG/Site/cartucho.png');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center center;
                height: 280px;
                width: 220px;
                justify-content: center;
                align-items: center;
            }
        </style>
    </head>
    <body class="d-flex flex-column min-vh-100">
        <div class="content flex-grow-1">
            <!-- Banner -->
            <div class="container-fluid text-white text-center p-3" style="background: linear-gradient(90deg, #465efb 0%, #c2ffd8 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="w-25 d-flex justify-content-start">
                    </div>
                    <h3 class="m-0">BEM VINDOS(AS) AO CARTUCHO VELHO</h3>
                    <div class="w-25"></div>

                </div>
            </div>

            <div class="container mt-3 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Todos os jogos</h2>
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
                    <div class="card-body">
                        <div class="row row-cols-1 row-cols-md-5 g-2">
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Jogos/mario.png" class="card-img-top mt-4" alt="...">
                                    
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Jogos/bomb.png" class="card-img-top mt-4" alt="...">
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Jogos/snake.avif" class="card-img-top mt-4" alt="...">
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Site/sem-imagem.jpg" class="card-img-top mt-4" alt="...">
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Site/sem-imagem.jpg" class="card-img-top mt-4" alt="...">
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Site/sem-imagem.jpg" class="card-img-top mt-4" alt="...">
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Site/sem-imagem.jpg" class="card-img-top mt-4" alt="...">
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Site/sem-imagem.jpg" class="card-img-top mt-4" alt="...">
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Site/sem-imagem.jpg" class="card-img-top mt-4" alt="...">
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <img src="dev/IMG/Site/sem-imagem.jpg" class="card-img-top mt-4" alt="...">
                                </div>
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
        
        <!-- Footer -->
        <?php include_once DEV_PATH . 'views/footer.php'?>

        <script>
            
        </script>
    </body>
</html>
