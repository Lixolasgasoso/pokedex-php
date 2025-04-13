<?php
require_once 'config.php';

/**
 * Busca dados da API
 */
function fetchPokeAPI($endpoint) {
    $url = POKEAPI_URL . $endpoint;
    
    $context = stream_context_create([
        'http' => ['ignore_errors' => true],
        'ssl' => ['verify_peer' => false]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false && function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 10
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    }
    
    return $response ? json_decode($response, true) : false;
}

function getPokemonDetails($id) {
    $data = fetchPokeAPI("pokemon/$id");
    if (!$data) return false;
    
    return [
        'id' => $data['id'],
        'name' => ucfirst($data['name']),
        'image' => $data['sprites']['front_default'],
        'types' => array_map(fn($t) => ucfirst($t['type']['name']), $data['types']),
        'height' => $data['height'] / 10, // Converte para metros
        'weight' => $data['weight'] / 10  // Converte para kg
    ];
}

/**
 * Lista todos os Pokémon da primeira geração
 */
function getAllFirstGenPokemons() {
    $data = fetchPokeAPI("pokemon?limit=" . POKEMONS_LIMIT);
    if (!$data) return false;
    
    $pokemons = [];
    foreach ($data['results'] as $pokemon) {
        $id = explode('/', trim($pokemon['url'], '/'))[6]; // Extrai o ID da URL
        $pokemons[] = [
            'id' => $id,
            'name' => ucfirst($pokemon['name'])
        ];
    }
    
    return $pokemons;
}
?>