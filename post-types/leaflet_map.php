<?php

/**
 * Registers the `leaflet_map` post type.
 */
function leaflet_map_init() {
	register_post_type( 'leaflet_map', array(
		'labels'                => array(
			'name'                  => __( 'Leaflet maps', 'leaflet_map' ),
			'singular_name'         => __( 'Leaflet map', 'leaflet_map' ),
			'all_items'             => __( 'All Leaflet maps', 'leaflet_map' ),
			'archives'              => __( 'Leaflet map Archives', 'leaflet_map' ),
			'attributes'            => __( 'Leaflet map Attributes', 'leaflet_map' ),
			'insert_into_item'      => __( 'Insert into leaflet map', 'leaflet_map' ),
			'uploaded_to_this_item' => __( 'Uploaded to this leaflet map', 'leaflet_map' ),
			'featured_image'        => _x( 'Featured Image', 'leaflet_map', 'leaflet_map' ),
			'set_featured_image'    => _x( 'Set featured image', 'leaflet_map', 'leaflet_map' ),
			'remove_featured_image' => _x( 'Remove featured image', 'leaflet_map', 'leaflet_map' ),
			'use_featured_image'    => _x( 'Use as featured image', 'leaflet_map', 'leaflet_map' ),
			'filter_items_list'     => __( 'Filter leaflet maps list', 'leaflet_map' ),
			'items_list_navigation' => __( 'Leaflet maps list navigation', 'leaflet_map' ),
			'items_list'            => __( 'Leaflet maps list', 'leaflet_map' ),
			'new_item'              => __( 'New Leaflet map', 'leaflet_map' ),
			'add_new'               => __( 'Add New', 'leaflet_map' ),
			'add_new_item'          => __( 'Add New Leaflet map', 'leaflet_map' ),
			'edit_item'             => __( 'Edit Leaflet map', 'leaflet_map' ),
			'view_item'             => __( 'View Leaflet map', 'leaflet_map' ),
			'view_items'            => __( 'View Leaflet maps', 'leaflet_map' ),
			'search_items'          => __( 'Search leaflet maps', 'leaflet_map' ),
			'not_found'             => __( 'No leaflet maps found', 'leaflet_map' ),
			'not_found_in_trash'    => __( 'No leaflet maps found in trash', 'leaflet_map' ),
			'parent_item_colon'     => __( 'Parent Leaflet map:', 'leaflet_map' ),
			'menu_name'             => __( 'Leaflet maps', 'leaflet_map' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title'),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'leaflet_map',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'leaflet_map_init' );

/**
 * Sets the post updated messages for the `leaflet_map` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `leaflet_map` post type.
 */
function leaflet_map_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['leaflet_map'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Leaflet map updated. <a target="_blank" href="%s">View leaflet map</a>', 'leaflet_map' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'leaflet_map' ),
		3  => __( 'Custom field deleted.', 'leaflet_map' ),
		4  => __( 'Leaflet map updated.', 'leaflet_map' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Leaflet map restored to revision from %s', 'leaflet_map' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Leaflet map published. <a href="%s">View leaflet map</a>', 'leaflet_map' ), esc_url( $permalink ) ),
		7  => __( 'Leaflet map saved.', 'leaflet_map' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Leaflet map submitted. <a target="_blank" href="%s">Preview leaflet map</a>', 'leaflet_map' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Leaflet map scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview leaflet map</a>', 'leaflet_map' ),
		date_i18n( __( 'M j, Y @ G:i', 'leaflet_map' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Leaflet map draft updated. <a target="_blank" href="%s">Preview leaflet map</a>', 'leaflet_map' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'leaflet_map_updated_messages' );
