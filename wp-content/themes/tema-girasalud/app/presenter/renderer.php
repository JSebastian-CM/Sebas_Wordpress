<?php
namespace App\Presenter;

class Renderer
{

    public static function render($extensiones)
    {   
        include(__DIR__ . '/../../servicios/servicio_head.php');
        foreach ($extensiones as $ext) {
            // $ext puede ser objeto App\Model\Extensiones o array, ajustar según tu Service
            $cpt = $ext->getCPT();
            $acf =$ext->getACF();

            // variables que usa tu parcial servicio_item.php
            $servicio_slug = $cpt['slug'] ?? '';
            $servicio_link = $cpt['permalink'] ?? '';
            $titulo = $acf['titulo'] ?? '';
            $descripcion = $acf['descripcion'] ?? '';
            $svg = $acf['svg'] ?? '';

            include(__DIR__ . '/../../servicios/servicio_item.php'); 
        }
  
        include(__DIR__ . '/../../servicios/servicio_footer.php'); 
    }
}