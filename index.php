<?php
require_once 'config.php';
require_once 'api_functions.php';

// 1. Obter Pokémons da API (oficiais da primeira geração)
$pokemonsOficiais = getAllFirstGenPokemons();

// 2. Obter Pokémons customizados armazenados na sessão (caso existam)
$pokemonsCustom = $_SESSION['pokemons'] ?? [];

// 3. Combinar Pokémons oficiais e customizados em um único array
$pokemons = array_merge($pokemonsOficiais, $pokemonsCustom);

// 4. Filtro por nome ou número, vindo da URL (?filtro=...)
$filtro = $_GET['filtro'] ?? '';
$filtrados = [];

// Se um filtro for fornecido, filtra os Pokémons
if ($filtro !== '') {
    // Filtrar por nome (contendo) ou número (começando com)
    foreach ($pokemons as $pokemon) {
        if (
            stripos($pokemon['name'], $filtro) !== false ||  // Filtra por nome
            strpos((string)$pokemon['id'], $filtro) === 0     // Filtra por ID (número)
        ) {
            $filtrados[] = $pokemon;
        }
    }
} else {
    // Nenhum filtro → mostra todos os Pokémons
    $filtrados = $pokemons;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pokedex Completa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Pokédex - Geração Oficial + Customizada</h1>

        <!-- Exibe informações de login ou botão para login -->
        <?php if (isset($_SESSION['logado'])): ?>
            <div class="user-info">
                Logado como: <?= $_SESSION['username'] ?>
                <a href="protegido.php">(Área Admin)</a> | 
                <a href="logout.php">Sair</a>
            </div>
        <?php else: ?>
            <a href="login.php" class="login-btn">Login Admin</a>
        <?php endif; ?>

        <!-- Formulário de busca -->
        <form method="get" action="index.php">
            <input 
                type="text" 
                name="filtro" 
                placeholder="Buscar por nome ou número" 
                value="<?= htmlspecialchars($filtro) ?>"
            >
            <button type="submit">Buscar</button>
        </form>
    </header>

    <main>
        <div class="pokedex-grid">
            <?php foreach ($filtrados as $pokemon): ?>
                <?php 
                // 5. Obtemos os detalhes do Pokémon conforme seu tipo (oficial ou customizado)
                if ($pokemon['id'] <= POKEMONS_LIMIT) {
                    // Se o Pokémon for oficial (ID <= POKEMONS_LIMIT), buscamos os detalhes da API
                    $details = getPokemonDetails($pokemon['id']);
                } else {
                    // Se for um Pokémon customizado, usamos os dados já disponíveis na sessão
                    $details = [
                        'image'  => $pokemon['image'],
                        'types'  => $pokemon['types'],
                        'height' => $pokemon['height'],
                        'weight' => $pokemon['weight']
                    ];
                }
                ?>

                <!-- Card do Pokémon -->
                <div class="pokemon-card <?= $pokemon['id'] > POKEMONS_LIMIT ? 'custom' : '' ?>">
                    <img src="<?= $details['image'] ?>" alt="<?= $pokemon['name'] ?>">
                    
                    <h3>
                        <?= $pokemon['name'] ?> 
                        <span>#<?= str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT) ?></span>
                        
                        <!-- Marca customizada para Pokémons criados -->
                        <?php if ($pokemon['id'] > POKEMONS_LIMIT): ?>
                            <span class="custom-badge">CUSTOM</span>
                        <?php endif; ?>
                    </h3>

                    <!-- Exibe os tipos do Pokémon -->
                    <div class="types">
                        <?php foreach ($details['types'] as $type): ?>
                            <span class="type type-<?= strtolower($type) ?>">
                                <?= $type ?>
                            </span>
                        <?php endforeach; ?>
                    </div>

                    <!-- Link para página de detalhes -->
                    <a href="detalhes.php?id=<?= $pokemon['id'] ?>" class="btn">Ver Mais</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
