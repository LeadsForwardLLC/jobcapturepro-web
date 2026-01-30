<?php
/**
 * Template Name: Early Access Success
 *
 * Shown after successful Early Access form submission.
 * Message comes from hardcoded config (jcp_core_get_early_access_form_config).
 * URL: /early-access-success/ (also routed via template-routes when no WP page exists).
 *
 * @package JCP_Core
 */

get_header();

$config        = function_exists( 'jcp_core_get_early_access_form_config' ) ? jcp_core_get_early_access_form_config() : [];
$default_msg   = isset( $config['success_message'] ) ? $config['success_message'] : "Thanks for signing up. We'll be in touch soon with early-bird pricing and next steps.";
// Default is backup only: never show when page content is set in WP admin.
$page_content = '';
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$page_content = get_post_field( 'post_content', get_the_ID() );
		break;
	}
	rewind_posts();
}
$supporting = trim( (string) $page_content );
$subtitle   = $supporting !== '' ? $supporting : $default_msg;
?>
<main class="jcp-marketing jcp-early-access-page jcp-success-page">
  <section class="jcp-section rankings-section jcp-success-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1>You're on the list</h1>
        <?php if ( $supporting !== '' ) : ?>
          <div class="jcp-page-intro jcp-archive-intro"><?php echo apply_filters( 'the_content', $page_content ); ?></div>
        <?php else : ?>
          <p class="rankings-subtitle"><?php echo esc_html( $subtitle ); ?></p>
        <?php endif; ?>
      </div>
      <div class="jcp-form-actions jcp-success-actions">
        <a class="btn btn-primary" href="<?php echo esc_url( home_url( '/early-access' ) ); ?>"><?php esc_html_e( 'Back to Early Access', 'jcp-core' ); ?></a>
        <a class="btn btn-secondary" href="<?php echo esc_url( home_url( '/demo' ) ); ?>"><?php esc_html_e( 'See the Demo', 'jcp-core' ); ?></a>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
