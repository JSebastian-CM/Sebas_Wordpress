<?php
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