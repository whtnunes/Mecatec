<?php
session_start(); // Adicione isso no topo do arquivo

// Configurações do PostgreSQL
$host = 'localhost';
$port = '5432';
$dbname = 'mecanica';
$user = 'postgres';
$password = 'admin';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($email && $senha) {
        $conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $conn = pg_connect($conn_string);

        if (!$conn) {
            $erro = 'Erro ao conectar ao banco de dados.';
        } else {
            $result = pg_query_params(
                $conn,
                'SELECT senha_hash FROM usuarios WHERE email = $1',
                array($email)
            );
            if ($row = pg_fetch_assoc($result)) {
                if (password_verify($senha, $row['senha_hash'])) {
                    $_SESSION['usuario_logado'] = true; // <-- Marque o usuário como logado
                    pg_close($conn);
                    header('Location: Mecatec.html');
                    exit();
                } else {
                    $erro = 'Usuário ou senha incorretos.';
                }
            } else {
                $erro = 'Usuário ou senha incorretos.';
            }
            pg_close($conn);
        }
    } else {
        $erro = 'Preencha todos os campos!';
    }
}
?>


