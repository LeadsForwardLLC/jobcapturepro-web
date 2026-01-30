<?php
/**
 * Template Name: Contact Success
 *
 * Shown after successful Contact form submission.
 * URL: /contact-success/ (routed via template-routes when no WP page exists).
 * Optional query param: attachment_omitted=1 to show attachment notice.
 *
 * @package JCP_Core
 */

get_header();

$attachment_omitted = isset( $_GET['attachment_omitted'] ) && $_GET['attachment_omitted'] === '1';
$message = "Thank you. Your message has been sent. We'll get back to you within one business day.";
if ( $attachment_omitted ) {
    $message .= " The attachment could not be included due to server size limits—please email it separately if needed.";
}
?>
<main class="jcp-marketing jcp-contact-page jcp-success-page">
  <section class="jcp-section rankings-section jcp-success-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1>Message sent</h1>
        <p class="rankings-subtitle"><?php echo esc_html( $message ); ?></p>
      </div>
      <div class="jcp-form-actions jcp-success-actions">
        <a class="btn btn-primary" href="/contact">Back to Contact</a>
        <a class="btn btn-secondary" href="/demo">See the Demo</a>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
