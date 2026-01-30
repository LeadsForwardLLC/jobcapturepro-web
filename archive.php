<?php
/**
 * Archive Template
 * 
 * Displays archive pages (category, tag, author, date archives).
 *
 * @package JCP_Core
 */

get_header();
?>

<main class="jcp-marketing">
  <!-- Hero Section (same spacing as blog index / category / author / tag) -->
  <section class="jcp-section rankings-section jcp-archive-hero-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1>
          <?php
          if ( is_category() ) {
            single_cat_title();
          } elseif ( is_tag() ) {
            single_tag_title();
          } elseif ( is_author() ) {
            the_author();
          } elseif ( is_date() ) {
            if ( is_year() ) {
              echo esc_html( get_the_date( 'Y' ) );
            } elseif ( is_month() ) {
              echo esc_html( get_the_date( 'F Y' ) );
            } elseif ( is_day() ) {
              echo esc_html( get_the_date() );
            }
          } else {
            esc_html_e( 'Archives', 'jcp-core' );
          }
          ?>
        </h1>
        <?php
        $description = get_the_archive_description();
        if ( $description ) :
          ?>
          <p class="rankings-subtitle"><?php echo wp_kses_post( $description ); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Archive Posts Section -->
  <section class="jcp-section rankings-section">
    <div class="jcp-container">
      <?php if ( have_posts() ) : ?>
        <div class="jcp-blog-grid">
          <?php
          while ( have_posts() ) :
            the_post();
            get_template_part( 'templates/content/content', 'post-card' );
          endwhile;
          ?>
        </div>

        <?php
        get_template_part( 'templates/partials/pagination' );
      else :
        get_template_part( 'templates/content/content', 'none' );
      endif;
      ?>
    </div>
  </section>
</main>

<?php
get_footer();
