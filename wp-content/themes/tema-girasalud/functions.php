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

function obtenerACF() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_68ee7a2f3af91',
	'title' => 'Campos girasalud',
	'fields' => array(
		array(
			'key' => 'field_68ee7a3808974',
			'label' => 'titulo',
			'name' => 'titulo',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_68ee7aab08975',
			'label' => 'descripcion',
			'name' => 'descripcion',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_68ee7b53df900',
			'label' => 'svg',
			'name' => 'svg',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
			'allow_in_bindings' => 0,
			'preview_size' => 'medium',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );
} 

add_action( 'acf/include_fields', 'obtenerACF');

function cptui_register_my_cpts_principal() {

	/**
	 * Post Type: Girasalud.
	 */

	$labels = [
		"name" => esc_html__( "Girasalud", "tema-girasalud" ),
		"singular_name" => esc_html__( "Girasalud", "tema-girasalud" ),
		"menu_name" => esc_html__( "Girasalud", "tema-girasalud" ),
		"all_items" => esc_html__( "Todas las entradas", "tema-girasalud" ),
		"add_new" => esc_html__( "Añadir nueva entrasa", "tema-girasalud" ),
		"add_new_item" => esc_html__( "Añadir nuevas entradas", "tema-girasalud" ),
		"edit_item" => esc_html__( "Editar entrada", "tema-girasalud" ),
		"new_item" => esc_html__( "Nueva entrada", "tema-girasalud" ),
		"view_item" => esc_html__( "Ver Entrada", "tema-girasalud" ),
		"view_items" => esc_html__( "Ver Entradas", "tema-girasalud" ),
		"search_items" => esc_html__( "Buscar Entradas", "tema-girasalud" ),
		"not_found" => esc_html__( "No se han encontrado entradas", "tema-girasalud" ),
		"not_found_in_trash" => esc_html__( "No se han encontrado enrtadas en la papelera", "tema-girasalud" ),
];

	$args = [
		"label" => esc_html__( "Girasalud", "tema-girasalud" ),
		"labels" => $labels,
		"description" => "Pagina de inicio del sitio web",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"can_export" => false,
		"rewrite" => [ "slug" => "principal", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-editor-kitchensink",
		"supports" => [ "title", "editor", "thumbnail", "page-attributes" ],
		"taxonomies" => [ "category", "post_tag" ],
		"show_in_graphql" => false,
	];

	register_post_type( "principal", $args );
}

add_action( 'init', 'cptui_register_my_cpts_principal' );

