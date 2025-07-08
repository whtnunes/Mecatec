<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Mecatec</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>

       
        body {
            background-color: rgb(58, 2, 2);
            font-family: 'Poppins', sans-serif;
            background-image: url('Imagens/motor.jpg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .cadastro-container {
            background: rgba(0,0,0,0.8);
            max-width: 400px;
            margin: 80px auto;
            padding: 32px 32px 24px 32px;
            border-radius: 12px;
            box-shadow: 0 0 16px #0000005f;
            color: #fff;
        }
        .cadastro-container h2 {
            text-align: center;
            margin-bottom: 24px;
            font-weight: 700;
            color: #fff;
        }
        .cadastro-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .cadastro-container input[type="text"],
        .cadastro-container input[type="email"],
        .cadastro-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: none;
            border-radius: 6px;
            background: #222;
            color: #fff;
        }
        .cadastro-container button {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            font-size: 18px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }
        .cadastro-container button:hover {
            background: #fff;
            color: #000;
        }
        .mensagem-erro {
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 12px;
        }
        .mensagem-sucesso {
            color: #4caf50;
            text-align: center;
            margin-bottom: 12px;
        }
    
        /* ... seu CSS ... */
    </style>
</head>

<body>
    <div class="cadastro-container">
        <h2>Cadastro de Usuário</h2>
        <?php if(isset($_GET['email'])): ?>
            <div class="mensagem-erro">E-mail já cadastrado</div>
        <?php endif; ?>
        <?php if(isset($_GET['sucesso'])): ?>
            <div class="mensagem-sucesso">Cadastro realizado com sucesso!</div>
        <?php endif; ?>
        <form method="POST" >
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" required>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Cadastrar</button>
            
        </form>

        <form method="POST" action="MecatecLogin.php">
            <button type="submit" style="width:100%;background:transparent;border:1px solid #fff;color:#fff;font-size:18px;border-radius:6px;cursor:pointer;transition:0.3s;margin-top:18px;">Faça o Login </button>
        </a>

        </form>
    </div>

<?php
// Configurações do PostgreSQL
$host = 'localhost';
$port = '5432';
$dbname = 'mecanica';
$user = 'postgres';
$password = 'admin';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($nome && $email && $senha) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
        $conn = pg_connect($conn_string);

        if (!$conn) {
            $erro = 'Erro ao conectar ao banco de dados.';
        } else {
            // Verifica se o e-mail já existe
            $verifica = pg_query_params(
                $conn,
                'SELECT 1 FROM usuarios WHERE email = $1',
                array($email)
            );
            if (pg_num_rows($verifica) > 0) {
                $erro = 'E-mail já cadastrado.';
                header('Location: MecatecCadastro.php?email=1');
            } else {
                $result = pg_query_params(
                    $conn,
                    'INSERT INTO usuarios (nome, email, senha_hash) VALUES ($1, $2, $3)',
                    array($nome, $email, $senha_hash)
                );
                if ($result) {
                    pg_close($conn);
                    header('Location: MecatecCadastro.php?sucesso=1');
                    exit();
                } else {
                    $erro = 'Erro ao cadastrar usuário.';
                }
            }
            pg_close($conn);
        }
    } else {
        $erro = 'Preencha todos os campos!';
    }
}
?>
</body>
</html>
