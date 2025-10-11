<?php

namespace Tests; 

use PHPUnit\Framework\TestCase;

class CadastroTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //$_SESSION = [];

        $this->conn = new \mysqli( 
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASS'),
            getenv('DB_NAME')
        );

        if ($this->conn->connect_error) {
            $this->fail("Falha na conexão com o banco de dados 'cv': " . $this->conn->connect_error);
        }

        $this->conn->query("TRUNCATE TABLE USUARIOS");
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testCadastroComSucesso()
    {
        $_POST['email-cadastro'] = 'teste@sucesso.com';
        $_POST['user-cadastro'] = 'teste';
        $_POST['password-cadastro'] = 'senhaforte123';

        ob_start();
        include __DIR__ . '/../site/dev/exec/cadastro_exec.php';
        $output = ob_get_clean(); 

        // DEBUG: Descomente para ver o que o script está outputando
        //echo "\nOutput do script (Cadastro Com Sucesso):\n" . $output . "\n";
        // DEBUG: Descomente para ver o conteúdo da $_SESSION
        //var_dump($_SESSION);

        $result = $this->conn->query("SELECT * FROM USUARIOS WHERE Email = 'teste@sucesso.com'");
        $this->assertEquals(1, $result->num_rows, "O usuário deveria ter sido inserido no banco de dados. Saída do script: " . $output);
        $this->assertEquals('Cadastro realizado com sucesso! Faça seu login', $_SESSION['msg']['texto']);
        $this->assertEquals('success', $_SESSION['msg']['tipo']);
    }

    public function testCadastroComEmailDuplicado()
    {
        $this->conn->query("INSERT INTO USUARIOS (User, Email, Senha) VALUES ('admin', 'existente@email.com', 'hashfalso')");
        
        $_POST['email-cadastro'] = 'existente@email.com';
        $_POST['user-cadastro'] = 'novousuario';
        $_POST['password-cadastro'] = 'senhaforte123';
        
        ob_start();
        include __DIR__ . '/../site/dev/exec/cadastro_exec.php';
        //$output = ob_get_clean();

        // DEBUG:
        //echo "\nOutput do script (Email Duplicado):\n" . $output . "\n";
        //var_dump($_SESSION);

        $this->assertEquals('O nome de usuário ou e-mail já está em uso.', $_SESSION['msg']['texto']);
        $this->assertEquals('warning', $_SESSION['msg']['tipo']);
    }
    
    public function testCadastroComSenhaCurta()
    {
        $_POST['email-cadastro'] = 'teste@borda.com';
        $_POST['user-cadastro'] = 'borda';
        $_POST['password-cadastro'] = '123'; 

        ob_start();
        include __DIR__ . '/../site/dev/exec/cadastro_exec.php';
        ob_end_clean();
        
        $this->assertEquals('A senha deve ter pelo menos 8 caracteres', $_SESSION['msg']['texto']);
        $this->assertEquals('warning', $_SESSION['msg']['tipo']);
    }

    public function testCadastroComCamposVazios()
    {
        $_POST['email-cadastro'] = '';
        $_POST['user-cadastro'] = '';
        $_POST['password-cadastro'] = '';

        ob_start();
        include __DIR__ . '/../site/dev/exec/cadastro_exec.php';
        ob_end_clean();

        $this->assertEquals('Todos os campos são obrigatórios.', $_SESSION['msg']['texto']);
        $this->assertEquals('warning', $_SESSION['msg']['tipo']);
    }
}