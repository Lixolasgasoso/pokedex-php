<?php
require 'config.php';
require 'api_functions.php';

// Obtém a lista de Pokémon
$pokemons = getAllFirstGenPokemons();

// Fallback offline caso a API falhe
if (!$pokemons) {
    $pokemons = [
        ['id' => 1, 'name' => 'Bulbasaur'],
        ['id' => 4, 'name' => 'Charmander'],
        ['id' => 7, 'name' => 'Squirtle']
    ];
    $offlineMode = true;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pokedex 1.0.2</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Pokédex - Primeira Geração</h1>
        <?php if (isset($offlineMode)): ?>
            <div class="warning">⚠️ Modo offline: Dados limitados</div>
        <?php endif; ?>
    </header>

    <main>
        <div class="pokedex-grid">
            <?php foreach ($pokemons as $pokemon): ?>
                <?php $details = getPokemonDetails($pokemon['id']); ?>
                <div class="pokemon-card">
                    <?php if ($details): ?>
                        <img src="<?= $details['image'] ?>" alt="<?= $pokemon['name'] ?>">
                        <h3><?= $pokemon['name'] ?> <span>#<?= str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT) ?></span></h3>
                        <div class="types">
                            <?php foreach ($details['types'] as $type): ?>
                                <span class="type type-<?= strtolower($type) ?>"><?= $type ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="error-box">
                            ⚠️ #<?= $pokemon['id'] ?>: Dados incompletos
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>