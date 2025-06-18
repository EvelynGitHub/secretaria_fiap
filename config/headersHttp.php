<?php

// As origens permitidas. Em produção, substituir pelos domínios reais.
$origensPermitidas = ['http://localhost:8080', 'null', 'http://127.0.0.1:5500']; // 'null' é para testar arquivos locais (file://)

// Os métodos HTTP permitidos
$metodosPermitidos = 'GET, POST, PUT, DELETE, OPTIONS';

// Os cabeçalhos permitidos que o cliente pode enviar
// É CRUCIAL incluir 'Content-Type' e 'Authorization' se estiver usando-os (no caso JWT usa)
$headersPermitidos = 'Content-Type, Authorization, Accept';

// Define o cabeçalho Access-Control-Allow-Origin

// --- Início da Lógica CORS ---

// 1. Define a origem da requisição atual
$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? null; // Usa o operador null coalescing para lidar com 'HTTP_ORIGIN' não definido

// 2. Verifica se é uma requisição de origem cruzada
$isCorsRequest = !is_null($requestOrigin);

// 3. Verifica se a origem da requisição é permitida para CORS
$isOriginAllowedForCORS = $isCorsRequest && in_array($requestOrigin, $origensPermitidas);

// 4. Lógica para definir o cabeçalho Access-Control-Allow-Origin
if ($isOriginAllowedForCORS) {
    // Se a origem é permitida para CORS, definimos o cabeçalho ACAO com a origem exata.
    header("Access-Control-Allow-Origin: " . $requestOrigin);
} else if (!$isCorsRequest) {
    // Se NÃO é uma requisição CORS (ou seja, é same-origin (da própria origem) ou file:// sem Origin cabeçalho),
    // NÃO definimos o cabeçalho Access-Control-Allow-Origin.
    // O navegador permite requisições same-origin por padrão.
    // Para 'null', se o navegador enviou 'Origin: null', ele cairá no $isOriginAllowedForCORS.
    // Se não enviou, não é uma requisição CORS formal para este middleware.
} else {
    // A requisição É CORS ($isCorsRequest é true), mas a origem NÃO é permitida ($isOriginAllowedForCORS é false).
    // Bloqueia a requisição para origens não permitidas.
    http_response_code(403);
    exit();
}


// Permite que credenciais (como cookies e cabeçalhos de autorização) sejam incluídas na requisição
header("Access-Control-Allow-Credentials: true");

// Define os métodos HTTP permitidos (para requisições preflight e reais)
header("Access-Control-Allow-Methods: " . $metodosPermitidos);

// Define os cabeçalhos permitidos (para requisições preflight)
header("Access-Control-Allow-Headers: " . $headersPermitidos);

// Define por quanto tempo (em segundos) a resposta preflight pode ser armazenada em cache
// 86400 segundos = 24 horas
header("Access-Control-Max-Age: 86400");

// Lida com a requisição OPTIONS (preflight)
// Se a requisição for um OPTIONS, o servidor deve apenas responder com os cabeçalhos CORS
// e não processar o corpo da requisição real.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Envia os cabeçalhos acima e termina a execução do script
    http_response_code(204); // Status 204 No Content para requisições OPTIONS bem-sucedidas
    exit(); // Encerra o script para não processar mais nada
}

// O restante do código PHP