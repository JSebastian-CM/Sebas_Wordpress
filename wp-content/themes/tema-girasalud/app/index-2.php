<?php

// Archivos necesarios
include_once __DIR__ . '/shared/Config.php';
$settings = include __DIR__ . '/settings.php';
//Planeo obtener el slug para clasificar el endpoint a llamar, pero por ahora lo dejo fijo
$slug = get_post_field('post_name', $post_id);

switch ($slug) {
    case 'inicio':
        $nombreRest = 'ExtensionRest';
        break;
    case 'principal':
        $nombreRest = 'PrincipalRest';
        break;
    case 'servicios':
        $nombreRest = 'ServiciosRest';
        break;
    default:
        header('Content-Type: application/json');
        echo json_encode(['error' => 'El endpoint solicitado no existe']);
        exit;
}

if (!file_exists(__DIR__ . "/rest/{$nombreRest}.php")) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'El endpoint solicitado no existe']);
    exit;
}

include_once __DIR__ . "/rest/{$nombreRest}.php";


$config = new \App\shared\Config($settings['cpts'], $settings['acfs']);
$className = "\\App\\Rest\\$nombreRest";
$extensionRest = new $className($config);
$resultado = $extensionRest->get();