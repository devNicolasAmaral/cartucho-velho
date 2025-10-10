<?php 
session_start();
include 'config.php';
include 'conexao_banco.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var(trim($_POST['email-cadastro'] ?? ''), FILTER_SANITIZE_EMAIL);
    $usuario = trim($_POST['user-cadastro'] ?? '');
    $senha = $_POST['password-cadastro'] ?? '';

    if (empty($email) || empty($usuario) || empty($senha)){
        $_SESSION['msg'] = ['texto' => 'Todos os campos são obrigatórios.', 'tipo' => 'warning'];
        header("Location: ../../autenticacao.php?action=cadastro");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['msg'] = ['texto' => 'O formato do e-mail é invalido.', 'tipo' => 'warning'];
        header("Location: ../../autenticacao.php?action=cadastro");
        exit();
    }

    if (mb_strlen($senha) < 8){
        $_SESSION['msg'] = ['texto' => 'A senha deve ter mais que 8 caracteres', 'tipo' => 'warning'];
        header("Location: ../../autenticacao.php?action=cadastro");
        exit();
    }

    
    $sqlCheck = "SELECT ID_Usuario FROM USUARIOS
                 WHERE User = ? OR Email = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("ss", $usuario, $email);
    $stmtCheck->execute();
    if ($stmtCheck->get_result()->num_rows > 0){
        $_SESSION['msg'] = ['texto' => 'O nome de usuário ou e-mail já está em uso.', 'tipo' => 'warning'];
        header("Location: ../../autenticacao.php?action=cadastro");
        exit();
    }
    $stmtCheck->close();
    
    $conn->begin_transaction();
    try {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $sqlUser = "INSERT INTO USUARIOS (Email, User, Senha) VALUES (?, ?, ?)";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bind_param("sss", $email, $usuario, $hash);
        if ($stmtUser->execute()) {
            $stmtUser->close();
            $conn->commit();
            $_SESSION['msg'] = ['texto' => 'Cadastro realizado com sucesso! Faça seu login', 'tipo' => 'success'];
            header("Location: ../../autenticacao.php?action=login");
            exit();
        }
        else {
            $_SESSION['msg'] = ['texto' => "Erro ao cadastrar usuário: " . $stmt_insert->error, 'tipo' => 'danger']; 
            header("Location: ../../autenticacao.php?action=cadastro");
            exit();
        }
        
    }
    catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        $_SESSION['msg'] = ['texto' => 'Ocorreu um erro no servidor. Por favor, tente novamente.', 'tipo' => 'danger'];
        header("Location: ../../autenticacao.php?action=cadastro");
        exit();
    }
}
else {
    header("Location: ../../autenticacao.php");
    exit();
}
?>