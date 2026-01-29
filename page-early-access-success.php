<?php
/**
 * Template Name: Early Access Success
 *
 * Shown after successful Early Access form submission.
 * Copy (heading, message) from Early Access Form ACF options.
 * URL: /early-access-success/ (also routed via template-routes when no WP page exists).
 *
 * @package JCP_Core
 */

get_header();

$config = function_exists( 'jcp_core_get_early_access_form_config' ) ? jcp_core_get_early_access_form_config() : [];
$message = isset( $config['success_message'] ) ? $config['success_message'] : "Thanks for signing up. We'll be in touch soon with early-bird pricing and next steps.";
?>
<main class="jcp-marketing jcp-early-access-page">
  <section class="jcp-section rankings-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1>You're on the list</h1>
        <p class="rankings-subtitle"><?php echo esc_html( $message ); ?></p>
      </div>
      <div class="jcp-form-actions" style="margin-top: var(--jcp-space-3xl);">
        <a class="btn btn-primary" href="/early-access">Back to Early Access</a>
        <a class="btn btn-secondary" href="/demo" style="margin-top: var(--jcp-space-md);">See the Demo</a>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
