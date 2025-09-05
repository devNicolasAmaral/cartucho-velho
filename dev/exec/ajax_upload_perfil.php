<?php
session_start();
header('Content-Type: application/json');

include 'config.php';
include 'conexao_banco.php';

if (!isset($_SESSION['ID_Usuario'])){
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

$id_user = $_SESSION['ID_Usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['arquivo'])){
    $arquivo = $_FILES['arquivo'];

    // Validação
    if ($arquivo['error'] !== UPLOAD_ERR_OK){
        echo json_encode(['success' => false, 'message' => 'Erro no upload do arquivo. Código: ' . $arquivo['error']]);
        exit();
    }

    $tamanhoMaximo = 2 * 1024 * 1024; // 2 MB
    if ($arquivo['size'] > $tamanhoMaximo) {
        echo json_encode(['success' => false, 'message' => 'O arquivo é muito grande. O limite é de 2 MB']);
        exit();
    }

    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($arquivo['type'], $tiposPermitidos)){
        echo json_encode(['success' => false, 'message' => 'Tipo de arquivo não permitido, Apenas JPG, PNG, GIF e WEBP']);
        exit();
    }

    // Salvamento
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $nomeUnico = "user_" . $id_user . "_" . uniqid() . "." . $extensao;

    $caminhoDestino = 'uploads/perfil/' . $nomeUnico;

    $caminhoCompletoFS = DEV_PATH . $caminhoDestino;

    if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompletoFS)){
        $sql = "UPDATE USUARIOS SET Foto_Perfil = ? WHERE ID_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $caminhoDestino, $id_user);
        $stmt->execute();

        $_SESSION['Foto_Perfil'] = $caminhoDestino;

        echo json_encode(['success' => true, 'message' => 'Imagem atualizada!', 'newPath' => BASE_URL . $caminhoDestino]);
        exit();
    }
    else{
        echo json_encode(['success' => false, 'message' => 'Falha ao mover o arquivo para o destino.']);
        exit();
    }
}
echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
?>