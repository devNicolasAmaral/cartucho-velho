<?php
include "config.php";
include "conexao_banco.php";

header('Content-Type: application/json');

$jogos = [];
$query = $_GET['jogo'] ?? '';
$searchTerm = "%" . $query . "%";

try {
    $stmt = $conn->prepare("
        SELECT ID_Jogo, Nome, Descrição, Caminho
        FROM JOGOS
        WHERE Nome LIKE ?
    ");

    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) 
        $jogos[] = $row;

    $stmt->close();
} 
catch (Exception $e) {
    error_log("Erro em busca_jogos.php: " . $e->getMessage());
    echo json_encode([]);
    exit; 
} 
finally {
    $conn->close();

}

echo json_encode($jogos);
?>