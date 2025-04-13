<?php
session_start();

// Verifica se o usuário está logado. Se não, redireciona para login.
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Se o formulário foi enviado (POST), processa o novo item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cria um novo item com os dados do formulário
    $novo_item = [
        'id' => rand(1, 1000), // Gera um ID aleatório para o novo Pokémon
        'name' => $_POST['nome'],
        'category' => $_POST['categoria']
    ];

    // Armazena o novo Pokémon na sessão
    $_SESSION['pokemons'][] = $novo_item;

    // Redireciona de volta para a página principal
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Item</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Adicionar Pokémon</h1>
    </header>

    <main>
        <!-- Formulário para adicionar novo Pokémon -->
        <form method="POST" action="adicionar_item.php">
            <label for="nome">Nome do Pokémon:</label>
            <input type="text" name="nome" required>

            <label for="categoria">Categoria:</label>
            <input type="text" name="categoria" required>

            <button type="submit">Adicionar</button>
        </form>
    </main>
</body>
</html>
