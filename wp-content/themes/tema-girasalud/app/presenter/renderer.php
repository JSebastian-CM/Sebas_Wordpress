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
        include(__DIR__ . '/../../servicios/servicio_head.php');
        //Extraer información relevante para la vista
        //Aun no uso el slug ni permalink pero los dejo por si los necesito luego
        $servicio_slug = $this->cptData['slug'] ?? '';
        $servicio_link = $this->cptData['permalink'] ?? '';
        $titulo = '';
        $descripcion = '';
        $imagen = '';
        

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
        
        if (!empty($this->cubeData )){
            foreach ($this->cubeData as $bloque) {
                include(__DIR__ . '/../../servicios/servicio_item.php');
            }
        }
        
        include(__DIR__ . '/../../servicios/servicio_footer.php'); 
    }
}