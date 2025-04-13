<?php
require 'config.php';

session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

// Verifica se o usuário está logado
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

// Processa o formulário de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    // Validação básica
    if (empty($_POST['nome']) || empty($_POST['tipos']) || empty($_POST['altura']) || empty($_POST['peso'])) {
        $_SESSION['erro'] = 'Preencha todos os campos obrigatórios!';
        header('Location: protegido.php');
        exit;
    }

    // Processa os dados do novo Pokémon
    $novoPokemon = [
        'id' => POKEMONS_LIMIT + count($_SESSION['pokemons'] ?? []) + 1,
        'name' => ucfirst(trim($_POST['nome'])),
        'types' => array_map('ucfirst', array_map('trim', explode(',', $_POST['tipos']))),
        'height' => (float)$_POST['altura'],
        'weight' => (float)$_POST['peso'],
        'image' => filter_var($_POST['imagem'], FILTER_VALIDATE_URL) ? $_POST['imagem'] : 'img/pokemon-default.png'
    ];

    // Adiciona à sessão
    $_SESSION['pokemons'][] = $novoPokemon;
    $_SESSION['sucesso'] = 'Pokémon cadastrado com sucesso!';
    header('Location: protegido.php');
    exit;
}

// Processa a remoção de Pokémon
if (isset($_GET['remover'])) {
    $id = (int)$_GET['remover'];
    if ($id > POKEMONS_LIMIT) {
        $_SESSION['pokemons'] = array_filter($_SESSION['pokemons'] ?? [], function($pokemon) use ($id) {
            return $pokemon['id'] !== $id;
        });
        $_SESSION['sucesso'] = 'Pokémon removido com sucesso!';
    }
    header('Location: protegido.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área Administrativa - Pokédex</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select {
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;
        }
        .btn { 
            background: #4CAF50; color: white; padding: 10px 15px; 
            border: none; border-radius: 4px; cursor: pointer; 
        }
        .btn-remover { background: #f44336; }
        .mensagem { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .sucesso { background: #dff0d8; color: #3c763d; }
        .erro { background: #f2dede; color: #a94442; }
        .pokemon-lista { margin-top: 20px; }
        .pokemon-card { 
            border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; 
            border-radius: 4px; display: flex; justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Área Administrativa</h1>
        <p>Bem-vindo, <strong><?= $_SESSION['username'] ?></strong>! | <a href="logout.php">Sair</a></p>

        <!-- Mensagens de status -->
        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="mensagem sucesso"><?= $_SESSION['sucesso'] ?></div>
            <?php unset($_SESSION['sucesso']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="mensagem erro"><?= $_SESSION['erro'] ?></div>
            <?php unset($_SESSION['erro']); ?>
        <?php endif; ?>

        <!-- Formulário de cadastro -->
        <h2>Cadastrar Novo Pokémon</h2>
        <form method="POST" action="protegido.php">
            <div class="form-group">
                <label>Nome do Pokémon:</label>
                <input type="text" name="nome" required>
            </div>

            <div class="form-group">
                <label>Tipos (separados por vírgula):</label>
                <input type="text" name="tipos" placeholder="Ex: Fogo, Voador" required>
            </div>

            <div class="form-group">
                <label>Altura (metros):</label>
                <input type="number" step="0.1" name="altura" required>
            </div>

            <div class="form-group">
                <label>Peso (kg):</label>
                <input type="number" step="0.1" name="peso" required>
            </div>

            <div class="form-group">
                <label>URL da Imagem (opcional):</label>
                <input type="url" name="imagem" placeholder="https://exemplo.com/imagem.png">
            </div>

            <button type="submit" name="cadastrar" class="btn">Cadastrar Pokémon</button>
        </form>

        <!-- Lista de Pokémons customizados -->
        <div class="pokemon-lista">
            <h2>Pokémons Customizados</h2>
            <?php if (!empty($_SESSION['pokemons'])): ?>
                <?php foreach ($_SESSION['pokemons'] as $pokemon): ?>
                    <div class="pokemon-card">
                        <div>
                            <strong><?= $pokemon['name'] ?></strong> 
                            (ID: <?= $pokemon['id'] ?>)<br>
                            Tipos: <?= implode(', ', $pokemon['types']) ?>
                        </div>
                        <div>
                            <a href="protegido.php?remover=<?= $pokemon['id'] ?>" 
                               class="btn btn-remover"
                               onclick="return confirm('Tem certeza que deseja remover este Pokémon?')">
                                Remover
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum Pokémon customizado cadastrado ainda.</p>
            <?php endif; ?>
        </div>

        <p><a href="index.php">Voltar para a Pokédex</a></p>
    </div>
</body>
</html>