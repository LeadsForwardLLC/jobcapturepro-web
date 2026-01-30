<?php
/**
 * Template Name: Early Access
 *
 * Renders the early access / founding crew page via JavaScript (data-jcp-page="early-access").
 * Hero title/supporting: WordPress title and content when set; otherwise defaults (backup only).
 * Passed to app via data-page-title and data-page-supporting so the app renders one hero (no duplicate).
 *
 * @package JCP_Core
 */

get_header();

$default_title      = __( 'Early Access', 'jcp-core' );
$default_supporting = __( "Join the early access list. We'll be in touch with next steps.", 'jcp-core' );
$page_title         = '';
$page_content       = '';
$supporting         = '';
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$page_title   = get_the_title();
		$page_content = get_post_field( 'post_content', get_the_ID() );
		$supporting   = trim( (string) $page_content );
		break;
	}
	rewind_posts();
}
$title            = trim( (string) $page_title ) !== '' ? $page_title : $default_title;
$subtitle         = $supporting !== '' ? $supporting : $default_supporting;
$supporting_plain = $supporting !== '' ? wp_strip_all_tags( $page_content ) : $subtitle;
?>
<div id="jcp-app" data-jcp-page="early-access" data-page-title="<?php echo esc_attr( $title ); ?>" data-page-supporting="<?php echo esc_attr( $supporting_plain ); ?>"></div>
<?php
get_footer();
