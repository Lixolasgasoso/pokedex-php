<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Teste de Conexão com PokeAPI</h2>";

$testUrl = 'https://pokeapi.co/api/v2/pokemon/1';
$response = @file_get_contents($testUrl);

if ($response === false) {
    echo "<p style='color:red'>❌ file_get_contents FALHOU. Erro: " . print_r(error_get_last(), true) . "</p>";
} else {
    echo "<p style='color:green'>✅ file_get_contents funcionou!</p>";
}

// Teste 2: Verifica cURL
if (function_exists('curl_version')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $testUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $curlResponse = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo "<p style='color:red'>❌ cURL FALHOU. Erro: " . curl_error($ch) . "</p>";
    } else {
        echo "<p style='color:green'>✅ cURL funcionou! Status HTTP: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "</p>";
    }
    curl_close($ch);
} else {
    echo "<p style='color:red'>❌ cURL NÃO ESTÁ ATIVO no PHP</p>";
}