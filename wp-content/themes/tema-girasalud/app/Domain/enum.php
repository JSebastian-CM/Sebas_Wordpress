<?php
//Evita magic strings
namespace App\Domain\enum;
// Se debe hacer estatico al metodo 
enum post_slug : string {
    case INICIO = 'inicio';
    case CITAS = 'citas';
    case CONTACTO = 'contacto';
    case ACERCA_DE = 'acerca_de';

    public static function getSlug($slug) {
        return match($slug){
            post_slug::INICIO => 'inicio', 
            post_slug::CITAS => 'citas',
            post_slug::CONTACTO => 'contacto',
            post_slug::ACERCA_DE => 'acerca_de'
        };
    }

}

// Prueba de uso del enum
$prueba =  "inicio";

echo post_slug::getSlug($prueba);
