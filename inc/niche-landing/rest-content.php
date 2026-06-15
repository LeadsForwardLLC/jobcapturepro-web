<?php
/**
 * REST API for industry page JSON content.
 *
 * @package JCP_Core
 */

/**
 * Register routes.
 */
function jcp_niche_register_rest_routes(): void {
	register_rest_route(
		'jcp/v1',
		'/niche/(?P<id>\d+)',
		[
			[
				'methods'             => 'GET',
				'callback'            => 'jcp_niche_rest_get_content',
				'permission_callback' => 'jcp_niche_rest_can_edit',
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
				'callback'            => 'jcp_niche_rest_save_content',
				'permission_callback' => 'jcp_niche_rest_can_edit',
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

/**
 * @param WP_REST_Request $request Request.
 * @return bool
 */
function jcp_niche_rest_can_edit( WP_REST_Request $request ): bool {
	$id = (int) $request->get_param( 'id' );
	return $id > 0 && current_user_can( 'edit_post', $id );
}

/**
 * @param WP_Post $post Post.
 */
function jcp_niche_rest_is_content_post( WP_Post $post ): bool {
	if ( $post->post_type === 'jcp_niche_landing' ) {
		return true;
	}
	return $post->post_type === 'page' && get_page_template_slug( $post->ID ) === 'page-referral-program.php';
}

/**
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function jcp_niche_rest_get_content( WP_REST_Request $request ) {
	$id   = (int) $request->get_param( 'id' );
	$post = get_post( $id );
	if ( ! $post || ! jcp_niche_rest_is_content_post( $post ) ) {
		return new WP_Error( 'not_found', __( 'Landing page not found.', 'jcp-core' ), [ 'status' => 404 ] );
	}
	return new WP_REST_Response(
		[
			'id'      => $id,
			'content' => jcp_niche_get_content( $id ),
			'url'     => get_permalink( $id ),
		]
	);
}

/**
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function jcp_niche_rest_save_content( WP_REST_Request $request ) {
	$id   = (int) $request->get_param( 'id' );
	$post = get_post( $id );
	if ( ! $post || ! jcp_niche_rest_is_content_post( $post ) ) {
		return new WP_Error( 'not_found', __( 'Landing page not found.', 'jcp-core' ), [ 'status' => 404 ] );
	}
	$body = $request->get_json_params();
	if ( empty( $body['content'] ) || ! is_array( $body['content'] ) ) {
		return new WP_Error( 'invalid', __( 'Missing content object.', 'jcp-core' ), [ 'status' => 400 ] );
	}
	jcp_niche_save_content( $id, $body['content'] );
	return new WP_REST_Response( [ 'success' => true, 'id' => $id ] );
}
