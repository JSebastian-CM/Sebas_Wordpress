<?php
namespace App\Presenter;

class Renderer
{
    private $cptData;
    private $acfData;
    private $cubeData;

    public function getInfo($extensiones)  // ← Quitado 'static'
    {   
        
        foreach ($extensiones as $ext) {
            // $ext puede ser objeto App\Model\Extensiones o array, ajustar según tu Service
            $this->cptData = $ext->getCPT();
            $this->acfData = $ext->getACF();
            $this->cubeData = $ext->getCubeWP();
 

        }
        
  
    }

    public function renderExtensiones()
    {   
        //Extraer información relevante para la vista
        //Aun no uso el slug ni permalink pero los dejo por si los necesito luego
        $servicio_slug = $this->cptData['slug'] ?? '';
        $servicio_link = $this->cptData['permalink'] ?? '';
        $titulo = '';
        $descripcion = '';
        $imagen = '';
        
        print_r($this->acfData); // Para depuración, muestra el contenido de ACF
        foreach ($this->acfData as $key => $value) {
            switch ($key) {
                case 'titulo':
                    $titulo = $value;
                    break;
                case 'descripcion':
                    $descripcion = $value;
                    break;
                case 'imagen':
                    $imagen = $value;
                    break;
                // Agrega más casos según tus campos ACF
            }
            
        }

        foreach ($this->cubeData as $bloque) {
            include(__DIR__ . '/../../servicios/servicio_item.php');
        }
        include(__DIR__ . '/../../servicios/servicio_head.php');
        
        include(__DIR__ . '/../../servicios/servicio_footer.php'); 
    }
}