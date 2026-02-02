<?php
/**
 * Template Name: Help Articles
 *
 * Displays the Help Articles listing for the help_article CPT with search/filter
 * and help-category taxonomy. Also used automatically when the page slug is "help".
 *
 * @package JCP_Core
 */

get_header();

$help_query = new WP_Query( [
	'post_type'      => 'help_article',
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
] );

$help_has_posts = $help_query->have_posts();
$total_posts    = $help_has_posts ? (int) $help_query->found_posts : 0;
$help_tax_slug  = 'help-category';
$help_terms     = get_terms( [ 'taxonomy' => $help_tax_slug, 'hide_empty' => true ] );
if ( is_wp_error( $help_terms ) || empty( $help_terms ) ) {
	$help_terms = [];
}

get_template_part( 'templates/help-articles-content', null, [
	'help_query'     => $help_query,
	'help_has_posts' => $help_has_posts,
	'total_posts'    => $total_posts,
	'help_terms'     => $help_terms,
] );

get_footer();
