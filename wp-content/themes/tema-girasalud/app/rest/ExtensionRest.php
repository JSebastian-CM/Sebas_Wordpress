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

    #Carga la configuracion al ExtensionService y este devuelve el contenido de las extensiones
    public function get()
    {
        $extensionServicio = new ExtensionService($this->config);
        $extensiones = $extensionServicio->getExtensiones();
        Renderer::render($extensiones);
        //aqui uso lo de renderizar con presenter
        // No se necesita el Mapper
    }
}