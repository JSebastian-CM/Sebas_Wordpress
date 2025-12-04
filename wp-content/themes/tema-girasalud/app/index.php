<?php

include_once __DIR__ . '/shared/Config.php';
include_once __DIR__. '/router/mapper.php';
include_once __DIR__ . '/rest/ExtensionRest.php';
$settings = include __DIR__ . '/settings.php';

use App\Router\Mapper;
use App\Rest\ExtensionRest;
use App\shared\Config;
// Ruteador común para manejar solicitudes GET


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['endpoint'])) {
    $method = $_SERVER['REQUEST_METHOD'];
    $mode = $_GET['endpoint'];
    
    if ($mode !== 'extension') {
        echo json_encode(['error' => 'Endpoint no válido']);
        exit;
    }else{
        $config = new \App\shared\Config($settings['cpts'], $settings['acfs']);
        Mapper::Route($mode, $method, $config);
    }

} else {
    
    echo json_encode(['error' => 'Falta el parámetro endpoint']);
    exit;   
}
