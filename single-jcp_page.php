<?php
/**
 * Single marketing page template.
 *
 * @package JCP_Core
 */

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		jcp_page_render( (int) get_the_ID() );
	}
}

get_footer();
