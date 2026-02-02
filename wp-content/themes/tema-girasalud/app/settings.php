<?php

namespace App;


$CPT =  [
        'girasalud' => [
            'post_type'      => 'principal',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]
    ];
$ACF =['titulo','descripcion','svg'];


// Retorna un array asociativo con la configuración
return [
    'cpts' => $CPT,
    'acfs' => $ACF,
];
