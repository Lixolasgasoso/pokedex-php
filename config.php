<?php
// Inicia a sessão para controle de login e dados persistentes
session_start();

// Configurações da PokéAPI

// URL base da PokéAPI
define('POKEAPI_URL', 'https://pokeapi.co/api/v2/');

// Quantidade de Pokémons da primeira geração (limitado a 151)
define('POKEMONS_LIMIT', 151);

define('CACHE_DIR', __DIR__ . '/cache');
ini_set('display_errors', 0);
error_reporting(E_ALL);
// Cria o diretório de cache
if (!file_exists(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0755, true);
}
?>
