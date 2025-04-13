<?php
session_start();
require 'config.php';

// Usuário e senha fixos (em produção, use banco de dados)
$usuarioValido = [
    'username' => 'admin',
    'senha' => password_hash('admin123', PASSWORD_BCRYPT) // Senha: "admin123" (hash)
];

// Se já estiver logado, redireciona para a área protegida
if (isset($_SESSION['logado'])) {
    header('Location: protegido.php');
    exit;
}

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Verifica credenciais
    if ($username === $usuarioValido['username'] && password_verify($senha, $usuarioValido['senha'])) {
        $_SESSION['logado'] = true;
        $_SESSION['username'] = $username;
        header('Location: protegido.php'); // Redireciona para área admin
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Pokédex Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
            text-align: center;
        }
        .login-box h2 {
            margin-top: 0;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-login {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-login:hover {
            background: #45a049;
        }
        .erro {
            color: #f44336;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Administrativo</h2>
        
        <?php if (isset($erro)): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label>Usuário:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required>
            </div>
            
            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
</body>
</html>