<?php

include_once __DIR__ . '/shared/Config.php';
include_once __DIR__ . '/rest/ExtensionRest.php';
$settings = include __DIR__ . '/settings.php';

use App\Rest\ExtensionRest;

// Verifica el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $config = new \App\shared\Config($settings['cpts'], $settings['acfs']);
    $extensionRest = new \App\Rest\ExtensionRest($config);
    $resultado = $extensionRest->get();
} else {
 echo 'Llamada incorrecta';   
}
