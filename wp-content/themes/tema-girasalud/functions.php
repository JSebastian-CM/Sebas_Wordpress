<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;


function tema_girasalud_scripts()
{
    // Enlazar el archivo style.css del tema
    wp_enqueue_style('tema-girasalud-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'tema_girasalud_scripts');

function tema_incluir_cpts() {
    $archivo = 'inc/cpt-ui.php'; 
    $ruta = get_template_directory() . '/' . $archivo;
    
    if (file_exists($ruta)) {
        require_once $ruta;
    } else {
        error_log("⚠️ No se encontró el archivo requerido: $archivo");
    }
    
}
add_action('after_setup_theme', 'tema_incluir_cpts');

function tema_incluir_acf_fields() {
    $archivo = 'inc/acf-fields.php';
    $ruta = get_template_directory() . '/' . $archivo;

    if (file_exists($ruta)) {
        require_once $ruta;
    } else {
        error_log("⚠️ No se encontró el archivo requerido: $archivo");
    }
    
}
add_action('init', 'tema_incluir_acf_fields');




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

//Faltan cambios en index-2, el codigo no carga bien el script de acuerdo al slug
function mi_tema_loop()
{
    if (is_singular('principal')) {
        while (have_posts()) {
            the_post();
            $post_id = get_the_ID();
            $slug = get_post_field('post_name', $post_id);
            require_once get_template_directory() . '/app/index.php';
        }
    } else {
        // fallback a inicio
        $query = new WP_Query([
            'post_type' => 'principal',
            'name' => 'inicio',
            'posts_per_page' => 1
        ]);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $slug = get_post_field('post_name', $post_id);
                require_once get_template_directory() . '/app/index.php';
            }
            wp_reset_postdata();
        }
    }
}
