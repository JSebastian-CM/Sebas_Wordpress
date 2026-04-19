<?php

namespace App;

include_once __DIR__ . '/shared/Config.php';

use App\shared\Config;

$CPT =  [
        'girasalud' => [
            'post_type'      => 'principal',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]
    ];
$ACF =['titulo','descripcion','imagen'];
$Pods_Inicio = ['titulo','descripcion','multimedia'];


// Retorna un array asociativo con la configuración
return [
    'cpts' => $CPT,
    'acfs' => $ACF,
    'pods_inicio' => $Pods_Inicio,
];
