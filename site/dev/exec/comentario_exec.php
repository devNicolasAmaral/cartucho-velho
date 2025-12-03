<?php
session_start();
include "config.php"; 
include "conexao_banco.php";

$response = ['success' => false, 'message' => '', 'html' => '', 'redirect' => false];

// 1. Verifica Login
if (!isset($_SESSION['ID_Usuario'])) {
    $_SESSION['msg'] = ['texto' => 'Faça login para comentar!', 'tipo' => 'error'];
    $response['message'] = 'Usuário não logado.';
    $response['redirect'] = true; // Avisa o JS para redirecionar
    echo json_encode($response);
    exit;
}

// 2. Processa o Comentário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['ID_Usuario'];
    $id_jogo = intval($_POST['id_jogo']);
    $texto = trim($_POST['comentario']);
    
    if (empty($texto)) {
        $response['message'] = 'O comentário não pode ser vazio.';
        echo json_encode($response);
        exit;
    }

    $sql = "INSERT INTO COMENTARIOS (ID_Jogo, ID_Usuario, Texto) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $id_jogo, $id_usuario, $texto);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Comentário enviado!';
        
        // Pega dados do usuário para montar o HTML de retorno
        // (Isso evita ter que recarregar a página para ver a foto/nome)
        $sqlUser = "SELECT User, Foto_Perfil FROM USUARIOS WHERE ID_Usuario = ?";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bind_param("i", $id_usuario);
        $stmtUser->execute();
        $user = $stmtUser->get_result()->fetch_assoc();
        
        $foto = ($user['Foto_Perfil']) ? "dev/".$user['Foto_Perfil'] : PERFIL_PLACEHOLDER;
        $nome = htmlspecialchars($user['User']);
        $data = date('d/m/y H:i');
        $comentarioTexto = nl2br(htmlspecialchars($texto));

        // Monta o HTML do novo comentário para o JS injetar
        $response['html'] = "
            <div class='comment-box animate-fade-in'>
                <div class='comment-avatar'>
                    <img src='{$foto}' alt='Avatar'>
                </div>
                <div class='comment-content'>
                    <div class='comment-header'>
                        <span>{$nome}</span>
                        <span class='comment-date'>{$data}</span>
                    </div>
                    <div>{$comentarioTexto}</div>
                </div>
            </div>
        ";
    } 
    else 
        $response['message'] = 'Erro ao salvar no banco.';
}

echo json_encode($response);
?>