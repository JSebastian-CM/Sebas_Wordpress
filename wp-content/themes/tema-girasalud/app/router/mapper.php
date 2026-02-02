<?php

namespace App\Router;
class Mapper
{

    //Se debe difrenciar entre procesar una extension o algo diferente, por lo que o debe usar ExtensionRest o cualquier otro Rest u otro metodo a futuro
    public static function Route($class, $method, $config){

        $restClass = ucfirst($class).'Rest';
        $classPath = "App\\Rest\\{$restClass}";
        
        if (!class_exists($classPath)) {
            return print('Clase REST no encontrada');

        }else{
            include_once __DIR__ . "/../rest/{$restClass}.php";   
        }

        if ($class == 'extension' && $method == 'GET') {

            $rest = new $classPath($config);
            $rest->get();
        
        } else {
            return print('Endpoint no válido o método no soportado');
        }

    }
}