<?php
/**
 * Marketing Pages CPT.
 *
 * URLs: /pages/{slug}/ (flat root slugs conflict with WP Pages).
 *
 * @package JCP_Core
 */

/**
 * Register jcp_page post type.
 */
function jcp_page_register_post_type(): void {
	$labels = [
		'name'               => __( 'Marketing Pages', 'jcp-core' ),
		'singular_name'      => __( 'Marketing Page', 'jcp-core' ),
		'menu_name'          => __( 'Marketing Pages', 'jcp-core' ),
		'add_new'            => __( 'Add Page', 'jcp-core' ),
		'add_new_item'       => __( 'Add Marketing Page', 'jcp-core' ),
		'edit_item'          => __( 'Edit Marketing Page', 'jcp-core' ),
		'new_item'           => __( 'New Marketing Page', 'jcp-core' ),
		'view_item'          => __( 'View Marketing Page', 'jcp-core' ),
		'search_items'       => __( 'Search Marketing Pages', 'jcp-core' ),
		'not_found'          => __( 'No marketing pages found.', 'jcp-core' ),
		'not_found_in_trash' => __( 'No marketing pages found in Trash.', 'jcp-core' ),
	];

	register_post_type(
		'jcp_page',
		[
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-media-document',
			'menu_position'       => 22,
			'has_archive'         => false,
			'rewrite'             => [
				'slug'       => 'pages',
				'with_front' => false,
			],
			'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ],
			'show_in_rest'        => true,
			'capability_type'     => 'page',
		]
	);
}
add_action( 'init', 'jcp_page_register_post_type' );

/**
 * Flush rewrite rules once after CPT registration.
 */
function jcp_page_maybe_flush_rewrites(): void {
	if ( get_option( 'jcp_page_rewrite_flush' ) === '1' ) {
		return;
	}
	flush_rewrite_rules( false );
	update_option( 'jcp_page_rewrite_flush', '1' );
}
add_action( 'init', 'jcp_page_maybe_flush_rewrites', 99 );

/**
 * Admin list: URL column.
 *
 * @param string[] $columns Columns.
 * @return string[]
 */
function jcp_page_admin_columns( array $columns ): array {
	$columns['jcp_page_url'] = __( 'URL', 'jcp-core' );
	return $columns;
}
add_filter( 'manage_jcp_page_posts_columns', 'jcp_page_admin_columns' );

/**
 * @param string $column  Column.
 * @param int    $post_id Post ID.
 */
function jcp_page_admin_column_content( string $column, int $post_id ): void {
	if ( $column !== 'jcp_page_url' ) {
		return;
	}
	$url = get_permalink( $post_id );
	if ( ! $url ) {
		echo '—';
		return;
	}
	echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( wp_make_link_relative( $url ) ) . '</a>';
}
add_action( 'manage_jcp_page_posts_custom_column', 'jcp_page_admin_column_content', 10, 2 );
