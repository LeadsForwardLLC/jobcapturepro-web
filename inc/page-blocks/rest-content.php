<?php
/**
 * REST API for JCP block page content.
 *
 * @package JCP_Core
 */

/**
 * Register routes.
 */
function jcp_page_register_rest_routes(): void {
	register_rest_route(
		'jcp/v1',
		'/page/(?P<id>\d+)',
		[
			[
				'methods'             => 'GET',
				'callback'            => 'jcp_page_rest_get_content',
				'permission_callback' => 'jcp_page_rest_can_edit',
				'args'                => [
					'id' => [
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
				],
			],
			[
				'methods'             => 'POST',
				'callback'            => 'jcp_page_rest_save_content',
				'permission_callback' => 'jcp_page_rest_can_edit',
				'args'                => [
					'id' => [
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
				],
			],
		]
	);
}
add_action( 'rest_api_init', 'jcp_page_register_rest_routes' );

/**
 * @param WP_REST_Request $request Request.
 */
function jcp_page_rest_can_edit( WP_REST_Request $request ): bool {
	$id = (int) $request->get_param( 'id' );
	return $id > 0 && current_user_can( 'edit_post', $id );
}

/**
 * @param WP_Post $post Post.
 */
function jcp_page_rest_is_content_post( WP_Post $post ): bool {
	return jcp_page_is_content_page( $post->ID );
}

/**
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function jcp_page_rest_get_content( WP_REST_Request $request ) {
	$id   = (int) $request->get_param( 'id' );
	$post = get_post( $id );
	if ( ! $post || ! jcp_page_rest_is_content_post( $post ) ) {
		return new WP_Error( 'not_found', __( 'Page not found.', 'jcp-core' ), [ 'status' => 404 ] );
	}
	$doc        = jcp_page_get_content( $id );
	$page_kind  = jcp_page_resolve_kind( $doc, $id );

	return new WP_REST_Response(
		[
			'id'         => $id,
			'content'    => jcp_page_get_content_flat( $id ),
			'blocks'     => $doc,
			'url'        => get_permalink( $id ),
			'page_kind'  => $page_kind,
			'registry'   => jcp_block_registry_public( $page_kind ),
		]
	);
}

/**
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function jcp_page_rest_save_content( WP_REST_Request $request ) {
	$id   = (int) $request->get_param( 'id' );
	$post = get_post( $id );
	if ( ! $post || ! jcp_page_rest_is_content_post( $post ) ) {
		return new WP_Error( 'not_found', __( 'Page not found.', 'jcp-core' ), [ 'status' => 404 ] );
	}
	$body = $request->get_json_params();
	if ( ! empty( $body['blocks'] ) && is_array( $body['blocks'] ) ) {
		$content = $body['blocks'];
		if ( ! empty( $body['content'] ) && is_array( $body['content'] ) ) {
			$content = jcp_page_merge_flat_into_blocks( $content, $body['content'] );
		}
	} elseif ( ! empty( $body['content'] ) && is_array( $body['content'] ) ) {
		$content = $body['content'];
	} else {
		return new WP_Error( 'invalid', __( 'Missing content object.', 'jcp-core' ), [ 'status' => 400 ] );
	}
	jcp_page_save_content( $id, $content );
	return new WP_REST_Response( [ 'success' => true, 'id' => $id ] );
}

/**
 * Legacy niche REST route (backward compatible).
 */
function jcp_niche_register_rest_routes(): void {
	register_rest_route(
		'jcp/v1',
		'/niche/(?P<id>\d+)',
		[
			[
				'methods'             => 'GET',
				'callback'            => 'jcp_page_rest_get_content',
				'permission_callback' => 'jcp_page_rest_can_edit',
				'args'                => [
					'id' => [
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
				],
			],
			[
				'methods'             => 'POST',
				'callback'            => 'jcp_page_rest_save_content',
				'permission_callback' => 'jcp_page_rest_can_edit',
				'args'                => [
					'id' => [
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					],
				],
			],
		]
	);
}
add_action( 'rest_api_init', 'jcp_niche_register_rest_routes' );
