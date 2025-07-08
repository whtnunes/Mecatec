<?php
session_start(); // Adicione isso no topo do arquivo

// Configurações do PostgreSQL
$host = 'localhost';
$port = '5432';
$dbname = 'mecanica';
$user = 'postgres';
$password = 'admin';

$erro = null; // Defina a variável antes de qualquer uso

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
        <h2>Login de Usuário</h2>
        <?php if(isset($erro) && $erro): ?>
            <div class="mensagem-erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>
        <form method="POST" action="MecatecLogin.php">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Entrar</button>
        </form>
        <a href="MecatecCadastro.php" style="display:block;margin-top:18px;text-align:center;">
            <button type="button" style="width:100%;background:transparent;border:1px solid #fff;color:#fff;font-size:18px;border-radius:6px;cursor:pointer;transition:0.3s;">Cadastrar</button>
        </a>
    </div>
</body>
</html>
