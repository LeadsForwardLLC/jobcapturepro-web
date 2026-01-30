<?php
/**
 * Standard Page Template
 * 
 * Default template for WordPress pages.
 * Content added via WP Admin editor renders cleanly with proper spacing and typography.
 *
 * @package JCP_Core
 */

get_header();
?>

<main class="jcp-marketing">
  <section class="jcp-section rankings-section jcp-archive-hero-section">
    <div class="jcp-container">
      <?php
      // Defaults are backup only: never show when WP title/content is set.
      while ( have_posts() ) :
        the_post();
        $page_content = get_post_field( 'post_content', get_the_ID() );
        $supporting   = trim( (string) $page_content );
        $default_text = ''; // Fallback when page has no content.
        ?>
      <div class="rankings-header">
        <h1><?php echo esc_html( get_the_title() ); ?></h1>
        <?php if ( $supporting !== '' ) : ?>
          <div class="jcp-page-intro jcp-archive-intro"><?php echo apply_filters( 'the_content', $page_content ); ?></div>
        <?php elseif ( $default_text !== '' ) : ?>
          <p class="rankings-subtitle"><?php echo esc_html( $default_text ); ?></p>
        <?php endif; ?>
      </div>
      <?php
      endwhile;
      ?>
    </div>
  </section>
  <?php
  // Per-page bottom CTA (reuses global CTA component; only when enabled and required fields set)
  if ( function_exists( 'get_field' ) ) {
    $page_id = get_the_ID();
    $show_cta = (bool) get_field( 'enable_page_cta', $page_id );
    $cta_headline = $show_cta ? trim( (string) get_field( 'page_cta_headline', $page_id ) ) : '';
    $cta_supporting = $show_cta ? trim( (string) get_field( 'page_cta_supporting_text', $page_id ) ) : '';
    $cta_btn_label = $show_cta ? trim( (string) get_field( 'page_cta_button_label', $page_id ) ) : '';
    $cta_btn_url   = $show_cta ? trim( (string) get_field( 'page_cta_button_url', $page_id ) ) : '';
    if ( $show_cta && $cta_headline !== '' && $cta_btn_label !== '' && $cta_btn_url !== '' ) {
      ?>
  <section class="jcp-section rankings-section">
    <div class="jcp-container">
      <div class="rankings-cta">
        <div class="cta-content">
          <h3><?php echo esc_html( $cta_headline ); ?></h3>
          <?php if ( $cta_supporting !== '' ) : ?>
          <p class="cta-paragraph"><?php echo esc_html( $cta_supporting ); ?></p>
          <?php endif; ?>
        </div>
        <div class="cta-button-wrapper">
          <a class="btn btn-primary rankings-cta-btn" href="<?php echo esc_url( $cta_btn_url ); ?>"><?php echo esc_html( $cta_btn_label ); ?></a>
        </div>
      </div>
    </div>
  </section>
  <?php
    }
  }
  ?>
</main>

<?php
get_footer();
