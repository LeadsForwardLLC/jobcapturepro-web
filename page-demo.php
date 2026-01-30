<?php
/**
 * Template Name: Demo
 *
 * When ?mode=run: Renders the live demo app via JavaScript (data-jcp-page="demo").
 * Otherwise: Renders the survey (steps + deck) via WordPress template parts.
 *
 * @package JCP_Core
 */

$demo_mode = isset( $_GET['mode'] ) && $_GET['mode'] === 'run'; // phpcs:ignore

if ( $demo_mode ) {
	get_header();
	?>
	<div id="jcp-app" data-jcp-page="demo"></div>
	<?php
	get_footer();
	return;
}

// Survey view: add body class and render survey template.
add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'survey-only';
		return $classes;
	}
);

get_header();
get_template_part( 'templates/survey/wrapper' );
get_footer();
