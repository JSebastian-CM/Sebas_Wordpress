<?php

include_once __DIR__ . '/dao/Conexion.php';
include_once __DIR__ . '/helper/Mapper.php';
include_once __DIR__ . '/shared/AppConfig.php';

$settings = include __DIR__ . '/settings.php';


if (!isset($_GET['endpoint'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Falta el parámetro endpoint']);
    exit;
}

$formateado = ucfirst($_GET['endpoint']);
$nombreRest = "{$formateado}Rest";

if (!file_exists(__DIR__ . "/rest/{$nombreRest}.php")) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'El endpoint solicitado no existe']);
    exit;
}

include_once __DIR__ . "/rest/{$nombreRest}.php";

$aCargar = "App\\Rest\\{$nombreRest}";

// Verifica el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    // Crea una instancia de PersonaRest y llama al método get
    $personaRest = new $aCargar($settings['appConfig']);
    $resultado = $personaRest->get();
    echo $resultado;
} else {
    // Responde con un error si el método no es GET
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Método no permitido']);
}
