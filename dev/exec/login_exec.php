<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';
include 'conexao_banco.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["user"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT ID_Usuario,
                                   Nome,
                                   Senha
                            FROM USUARIOS 
                            WHERE User = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dados = $result->fetch_assoc();
        $passHash = $dados['Senha'];

        if (password_verify($password, $passHash)) {
            $_SESSION['ID_Usuario'] = $dados['ID_Usuario'];
            $_SESSION['Nome'] = $dados['Nome'];
            $_SESSION['expire'] = strtotime('+300 minutes', strtotime('now'));

            $_SESSION["msg"] = ['texto' => "Olá " . htmlspecialchars($_SESSION['Nome']) . ", Login efetuado com sucesso!", 'tipo' => 'success'];
            mysqli_close($conn);    
            header('Location:' . BASE_URL . 'index.php');
            exit();
        }
        else {
            $_SESSION["msg"] = ['texto' => 'Usuário ou senha estão incorretos', 'tipo' => 'danger'];
            mysqli_close($conn);
            header('Location:' . BASE_URL . 'login.php');
            exit;
        }
    }
    else {
        $_SESSION["msg"] = ['texto' => 'Usuário ou senha estão incorretos', 'tipo' => 'danger'];
        mysqli_close($conn);
        header('Location:' . BASE_URL . 'login.php');
        exit;
    }
}
?>