<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

function tema_girasalud_scripts()
{
    // Enlazar el archivo style.css del tema
    wp_enqueue_style('tema-girasalud-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'tema_girasalud_scripts');

// Ganchos para el encabezado
function mi_tema_head()
{
    echo '<meta charset="' . get_bloginfo('charset') . '">' . "\n";
    echo '<meta test="sebitas">' . "\n";
}
add_action('wp_head', 'mi_tema_head');

function mi_tema_footer()
{
    include(__DIR__ . '/footer.php');
}
add_action('wp_footer', 'mi_tema_footer');


function mi_tema_header()
{
    include(__DIR__ . '/header.php');
}

/**
 * El Loop de WordPress
 * Este es el bucle básico para mostrar entradas.
 */
function mi_tema_loop()
{
    if (have_posts()) {        
        mostrar_servicios_en_home();        
        while (have_posts()) {
            the_post();
            
?>
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </div>
        <?php
        }
    } else {
        ?>
        <h2>No se encontraron entradas</h2>
<?php
    }
}


function mostrar_servicios_en_home()
{
    $args = array(
        'post_type'      => 'servicio',
        'posts_per_page' => -1, // Muestra todos los posts del custom post type 'servicio'
        'post_status'    => 'publish',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        //echo '<div class="servicios-container">'; // Contenedor para los servicios
        include(__DIR__ . '/servicios/servicio_head.php');
        while ($query->have_posts()) {
            $query->the_post();

            // Obtener el slug y el enlace del post
            $servicio_slug = get_post_field('post_name', get_post());
            $servicio_link = get_permalink();

            // Obtener los metadatos de ACF
            $titulo = get_field('titulo');
            $descripcion = get_field('descripcion');
            $svg = get_field('svg');

            include(__DIR__ . '/servicios/servicio_item.php');

            //echo '<div class="servicio-item">';

            // Mostrar el título y la descripción
            //echo '<h2><a href="' . esc_url($servicio_link) . '">' . esc_html($titulo) . '</a></h2>';
            //echo '<p>' . esc_html($descripcion) . '</p>';

            //echo '</div>'; // Cierre de .servicio-item
        }
        //echo '</div>'; // Cierre de .servicios-container
        include(__DIR__ . '/servicios/servicio_footer.php');

        wp_reset_postdata(); // Restablecer los datos del post a los datos originales
    } else {
        echo '<p>No hay servicios disponibles en este momento.</p>';
    }
}

/**
 * Cargar archivos personalizados del tema (CPT y ACF)
 */
function tema_incluir_archivos_personalizados() {
    $archivos = array(
        'inc/cpt.php',  // Custom Post Types
        'inc/acf.php',  // Campos ACF exportados
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



