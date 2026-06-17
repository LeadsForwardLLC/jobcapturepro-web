<?php
/**
 * Template Name: Home
 *
 * Homepage — block-rendered from global JCP block library.
 * Assign to the static front page in Settings → Reading.
 *
 * @package JCP_Core
 */

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		jcp_niche_render_page( (int) get_the_ID() );
	}
}

get_footer();
