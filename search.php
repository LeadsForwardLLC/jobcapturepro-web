<?php
/**
 * Search Results Template
 * 
 * Displays search results.
 *
 * @package JCP_Core
 */

get_header();
?>

<main class="jcp-marketing">
  <!-- Hero Section -->
  <section class="jcp-section rankings-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1>
          <?php
          printf(
            esc_html__( 'Search Results for: %s', 'jcp-core' ),
            '<span>' . esc_html( get_search_query() ) . '</span>'
          );
          ?>
        </h1>
        <?php
        global $wp_query;
        if ( $wp_query->found_posts > 0 ) :
          ?>
          <p class="rankings-subtitle">
            <?php
            printf(
              esc_html( _n( 'Found %d result', 'Found %d results', $wp_query->found_posts, 'jcp-core' ) ),
              esc_html( $wp_query->found_posts )
            );
            ?>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Search Results Section -->
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
        ?>
        <div class="jcp-empty-state">
          <p><?php esc_html_e( 'Sorry, no results found. Try different keywords.', 'jcp-core' ); ?></p>
          <?php get_search_form(); ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php
get_footer();
