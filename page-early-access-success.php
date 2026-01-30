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

$config = function_exists( 'jcp_core_get_early_access_form_config' ) ? jcp_core_get_early_access_form_config() : [];
$message = isset( $config['success_message'] ) ? $config['success_message'] : "Thanks for signing up. We'll be in touch soon with early-bird pricing and next steps.";
?>
<main class="jcp-marketing jcp-early-access-page jcp-success-page">
  <section class="jcp-section rankings-section jcp-success-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1>You're on the list</h1>
        <p class="rankings-subtitle"><?php echo esc_html( $message ); ?></p>
      </div>
      <div class="jcp-form-actions jcp-success-actions">
        <a class="btn btn-primary" href="/early-access">Back to Early Access</a>
        <a class="btn btn-secondary" href="/demo">See the Demo</a>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
