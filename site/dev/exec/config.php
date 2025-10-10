<?php
// Nome do SITE
define('NOME', 'Cartucho Velho');  

// Caminho do diretório Dev 
define('DEV_PATH', dirname(__DIR__). '/');

// URL base 
define('BASE_URL', 'http://localhost/htdocs/cartucho-velho/site/');
define('DEV_URL', BASE_URL . 'dev/');
define('IMG_PLACEHOLDER', DEV_URL . 'IMG/Site/sem-img.png');
define('PERFIL_PLACEHOLDER', DEV_URL . 'IMG/Site/sem-perfil.jpg');

// Credenciais do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'cv');
define('DB_USER', 'root');
define('DB_PASS', '1705');

// Timezone (opcional mas recomendado)
date_default_timezone_set('America/Sao_Paulo');
?>