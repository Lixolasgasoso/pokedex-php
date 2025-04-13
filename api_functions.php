<?php
require_once 'config.php';

/**
 * Função genérica para buscar dados da PokéAPI.
 * Usa `file_get_contents` e fallback para cURL caso necessário.
 */
function fetchPokeAPI($endpoint) {
    $url = POKEAPI_URL . $endpoint;

    // Configura contexto para permitir falhas de SSL e evitar warnings
    $context = stream_context_create([
        'http' => ['ignore_errors' => true],
        'ssl' => ['verify_peer' => false]
    ]);

    // Tenta obter os dados usando file_get_contents
    $response = @file_get_contents($url, false, $context);

    // Caso falhe e cURL esteja disponível, tenta com cURL
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

    // Retorna os dados decodificados ou false se falhar
    return $response ? json_decode($response, true) : false;
}

/**
 * Busca e retorna os detalhes completos de um Pokémon por ID.
 */
function getPokemonDetails($id) {
    $data = fetchPokeAPI("pokemon/$id");
    if (!$data) return false;

    // Garante que a chave 'types' existe antes de continuar
    if (isset($data['types'])) {
        return [
            'id'     => $data['id'],
            'name'   => ucfirst($data['name']),
            'image'  => $data['sprites']['front_default'],
            'types'  => array_map(fn($t) => ucfirst($t['type']['name']), $data['types']),
            'height' => $data['height'] / 10, // Altura em metros
            'weight' => $data['weight'] / 10  // Peso em kg
        ];
    }

    // Caso falte alguma informação essencial
    return false;
}

/**
 * Retorna todos os Pokémons da primeira geração (limitados por constante).
 */
function getAllFirstGenPokemons() {
    $data = fetchPokeAPI("pokemon?limit=" . POKEMONS_LIMIT);
    if (!$data) return false;

    $pokemons = [];

    foreach ($data['results'] as $pokemon) {
        // Extrai o ID da URL retornada pela API
        $id = explode('/', trim($pokemon['url'], '/'))[6];

        $pokemons[] = [
            'id'   => $id,
            'name' => ucfirst($pokemon['name'])
        ];
    }

    return $pokemons;
}
?>
