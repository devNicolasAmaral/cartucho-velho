<?php
$servername = getenv(DB_HOST) ?: 'localhost';
$username = getenv(DB_USER) ?: 'root';
$password = getenv(DB_PASS) ?: '591318362';
$dbname = getenv(DB_NAME) ?: 'cv';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
