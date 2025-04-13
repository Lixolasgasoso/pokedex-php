<?php
require 'config.php';
require 'api_functions.php';

// Verifica se foi passado um ID válido via GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de Pokémon inválido.";
    exit;
}

$id = (int) $_GET['id'];
$pokemon = null;

// Se for um Pokémon oficial da API (1ª geração)
if ($id <= POKEMONS_LIMIT) {
    $pokemon = getPokemonDetails($id);
} else {
    // Caso contrário, busca entre os Pokémons customizados armazenados na sessão
    if (isset($_SESSION['pokemons'])) {
        foreach ($_SESSION['pokemons'] as $p) {
            if ($p['id'] == $id) {
                $pokemon = [
                    'id' => $p['id'],
                    'name' => $p['name'],
                    'image' => $p['image'],
                    'types' => $p['types'],
                    'height' => $p['height'],
                    'weight' => $p['weight'],
                    'is_custom' => true
                ];
                break;
            }
        }
    }
}

// Se não encontrou o Pokémon, retorna erro 404
if (!$pokemon) {
    header('HTTP/1.1 404 Not Found');
    die('Pokémon não encontrado');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $pokemon['name'] ?> - Detalhes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .detalhes-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .pokemon-card img {
            width: 180px;
            height: 180px;
            object-fit: contain;
        }

        .info {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="detalhes-container">
    <h2>
        <?= $pokemon['name'] ?> 
        <span>#<?= str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT) ?></span>
    </h2>

    <img src="<?= $pokemon['image'] ?>" alt="<?= $pokemon['name'] ?>">

    <div class="types">
        <?php foreach ($pokemon['types'] as $type): ?>
            <span class="type type-<?= strtolower($type) ?>"><?= $type ?></span>
        <?php endforeach; ?>
    </div>

    <div class="info">
        <p><strong>Altura:</strong> <?= $pokemon['height'] ?> m</p>
        <p><strong>Peso:</strong> <?= $pokemon['weight'] ?> kg</p>
    </div>

    <a href="index.php" class="btn">⬅ Voltar</a>
</div>

</body>
</html>
