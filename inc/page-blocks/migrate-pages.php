<?php
/**
 * One-time migrations: existing WP pages → JCP Block Page template + blocks storage.
 *
 * @package JCP_Core
 */

/**
 * Migrate /referral-program/ to the unified JCP Block Page template.
 *
 * Preserves URL, post ID, Rank Math meta, and any saved page content.
 * Normalizes legacy flat JSON into blocks[] on first run.
 *
 * @return int Post ID or 0.
 */
function jcp_page_migrate_referral_program_to_jcp_blocks(): int {
	if ( function_exists( 'jcp_niche_seed_referral_program' ) ) {
		jcp_niche_seed_referral_program();
	}

	$page = get_page_by_path( 'referral-program', OBJECT, 'page' );
	if ( ! $page instanceof WP_Post ) {
		return 0;
	}

	$post_id = (int) $page->ID;

	$content = jcp_page_get_content( $post_id );
	jcp_page_save_content( $post_id, $content );

	update_post_meta( $post_id, '_wp_page_template', 'page-jcp-blocks.php' );

	return $post_id;
}

/**
 * Run page migrations once per environment after deploy.
 */
function jcp_page_maybe_migrate_pages(): void {
	if ( get_option( 'jcp_migrated_referral_jcp_blocks_v1' ) === '1' ) {
		return;
	}

	$id = jcp_page_migrate_referral_program_to_jcp_blocks();
	if ( $id > 0 ) {
		update_option( 'jcp_migrated_referral_jcp_blocks_v1', '1' );
	}
}
add_action( 'init', 'jcp_page_maybe_migrate_pages', 25 );
