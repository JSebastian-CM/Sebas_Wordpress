<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;


function tema_girasalud_scripts()
{
    // Enlazar el archivo style.css del tema
    wp_enqueue_style('tema-girasalud-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'tema_girasalud_scripts');

function tema_incluir_archivos_personalizados() {
    $archivos = array(
        'inc/cpt-ui.php',  
        'inc/acf-fields.php'  
    );

    foreach ($archivos as $archivo) {
        $ruta = get_template_directory() . '/' . $archivo;
        if (file_exists($ruta)) {
            require_once $ruta;
        } else {
            error_log("⚠️ No se encontró el archivo requerido: $archivo");
        }
    }
}
add_action('after_setup_theme', 'tema_incluir_archivos_personalizados');



// Ganchos para el encabezado
function mi_tema_head()
{
    echo '<meta charset="' . get_bloginfo('charset') . '">' . "\n";
    echo '<meta test="sebitas">' . "\n";
}
add_action('wp_head', 'mi_tema_head');

function mi_tema_header()
{
    include(__DIR__ . '/header.php');
}
add_action('wp_head', 'mi_tema_header');

function mi_tema_footer()
{
    include(__DIR__ . '/footer.php');
}
add_action('wp_footer', 'mi_tema_footer');


function mi_tema_loop()
{
    $args = array(
        'post_type' => 'principal',  //  Corregido: usa el slug real del CPT
        'name' => 'inicio',          // Busca por slug/name
        'posts_per_page' => 1
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            // Obtener el slug correctamente
            $post_id = get_the_ID();  //  Define $post_id aquí
            $slug = get_post_field('post_name', $post_id);  //  Obtiene el slug
            
            // Mostrar contenido básico si quieres
            the_content();
            
            // Ahora puedes usar $slug y $post_id en index-2.php
            // O integrar el código de index-2.php directamente aquí
            require_once get_template_directory() . '/app/index-2.php';
        endwhile;
        wp_reset_postdata();
    else :
        echo '<h2>No se encontró el post "inicio"</h2>';
    endif;
}


