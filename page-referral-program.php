<?php
/**
 * Template Name: Referral Program
 *
 * Affiliate / referral program landing page at /referral-program/.
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
