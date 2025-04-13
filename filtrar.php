<?php
require_once 'api_functions.php';

// Obtém todos os Pokémons da primeira geração
$pokemons = getAllFirstGenPokemons();

// Captura o valor do filtro da URL (GET)
$filtro = $_GET['filtro'] ?? '';
$resultados = [];

// Se o filtro foi preenchido, faz a busca por nome exato ou número
if ($filtro !== '') {
    foreach ($pokemons as $pokemon) {
        if (
            strcasecmp($pokemon['name'], $filtro) === 0 || // Nome (case-insensitive)
            $pokemon['id'] == $filtro                      // ID numérico
        ) {
            $resultados[] = $pokemon;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Filtrar Pokémons</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Filtrar Pokémon por Nome ou Número</h1>

    <!-- Formulário de busca -->
    <form method="get" action="filtrar.php">
        <input 
            type="text" 
            name="filtro" 
            placeholder="Nome ou número" 
            value="<?= htmlspecialchars($filtro) ?>" 
            required
        >
        <button type="submit">Buscar</button>
    </form>

    <?php if ($filtro !== ''): ?>
        <h2>Resultados para: <?= htmlspecialchars($filtro) ?></h2>

        <?php if (count($resultados) > 0): ?>
            <div class="pokedex-grid">
                <?php foreach ($resultados as $pokemon): ?>
                    <?php $details = getPokemonDetails($pokemon['id']); ?>

                    <div class="pokemon-card">
                        <img src="<?= $details['image'] ?>" alt="<?= $pokemon['name'] ?>">

                        <h3>
                            <?= $pokemon['name'] ?> 
                            <span>#<?= str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT) ?></span>
                        </h3>

                        <div class="types">
                            <?php foreach ($details['types'] as $type): ?>
                                <span class="type type-<?= strtolower($type) ?>"><?= $type ?></span>
                            <?php endforeach; ?>
                        </div>

                        <a href="detalhes.php?id=<?= $pokemon['id'] ?>">
                            <button>Ver Mais</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Nenhum Pokémon encontrado com esse critério.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
