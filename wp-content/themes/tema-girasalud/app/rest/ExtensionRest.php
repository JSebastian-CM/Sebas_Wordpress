<?php

namespace App\Rest;

include_once __DIR__ . '/../service/ExtensionService.php';
include_once __DIR__ . '/../presenter/renderer.php';
include_once __DIR__ . '/../shared/Config.php';

use App\Service\ExtensionService;
//use App\Helper\Mapper; descarto el mapper porque no hago transformacion de datos
use App\Presenter\Renderer;
use App\shared\Config;


class ExtensionRest
{
    private $config;

    public function __construct(Config $appConfig)
    {
        $this->config = $appConfig;
        
    }

    public function get()
    {
        $extensionServicio = new ExtensionService($this->config);
        $extensiones = $extensionServicio->getExtensiones();
        $renderer = new Renderer();
        $renderer->getInfo($extensiones);
        $renderer->renderExtensiones();
        //aqui uso lo de renderizar (mostrar en html) con presenter
        // No se necesita el Mapper
    }
}