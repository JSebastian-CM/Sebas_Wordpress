<?php


namespace App\Service;
include_once __DIR__ . '/../shared/Config.php';
include_once __DIR__ . '/../model/Extensiones.php';


use App\Model\Extensiones;
use App\shared\Config;

class ExtensionService
{
    private $Config;
    private $CPTs;
    private $ACFs;

    public function __construct(Config $appConfig)
    {
        $this->Config = $appConfig;
        $this->CPTs = $this->Config->getCPT();
        $this->ACFs = $this->Config->getACF();
    }

    public function getExtensiones()
    {
        $resultados = [];
        
        $query = new \WP_Query($this->CPTs['girasalud']);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post = get_post();

                $cptData = [
                    'ID'        => $post->ID,
                    'title'     => get_the_title($post),
                    'slug'      => get_post_field('post_name', $post),
                    'permalink' => get_permalink($post),
                ];

                // reiniciar por cada post y obtener ACF pasando el ID
                $acfData = [];
                foreach ($this->ACFs as $acf) {
                    $acfData[$acf] = get_field($acf, $post->ID);
                    
                }

                $extensiones = new Extensiones();
                $extensiones->setCPT($cptData);
                $extensiones->setACF($acfData);

                $resultados[] = $extensiones;
            }
            wp_reset_postdata();

        }

        return $resultados; 
    }
}