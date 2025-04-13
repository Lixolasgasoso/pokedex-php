<?php
// Importa as configurações globais e funções da API
require 'config.php';
require 'api_functions.php';

// Verifica se um ID foi passado via GET
if (isset($_GET['id'])) {
    // Converte o valor do ID para inteiro, por segurança
    $id = intval($_GET['id']);

    // Obtém e exibe a descrição do Pokémon
    echo getPokemonDescription($id);
}
?>
