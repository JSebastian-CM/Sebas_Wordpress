<?php

#Se en carga de enrutar a los diferentes endpoints dependiendo del slug que se le pase, retorna [en ocnstruccion]
#Cada slug debe llevar el nombre del contenido que muestra
class Router{
    function __construct($slug){
        $this->slug = $slug;
    }

    function route(){
        switch ($this->slug) {
            case 'inicio':
                $nombreRest = 'ExtensionRest';
                break;
            case 'acerca_de':
                $nombreRest = 'PrincipalRest';
                break;
            case 'citas':
                $nombreRest = 'ServiciosRest';
                break;
            case 'contacto':
                $nombreRest = 'ContactoRest';
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
    }
}
