<?php
/**
 * Blog Archive Template
 * 
 * Displays the blog posts archive (when blog is set as homepage).
 *
 * @package JCP_Core
 */

get_header();
?>

<main class="jcp-marketing">
  <!-- Hero Section (same spacing as category/author/archive) -->
  <section class="jcp-section rankings-section jcp-archive-hero-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?> Blog</h1>
        <?php
        $blog_subtitle = get_bloginfo( 'description' );
        if ( $blog_subtitle === '' ) {
          $blog_subtitle = __( 'Insights and updates for contractors.', 'jcp-core' );
        }
        ?>
        <p class="rankings-subtitle"><?php echo esc_html( $blog_subtitle ); ?></p>
      </div>
      <?php
      $posts_page_id = (int) get_option( 'page_for_posts' );
      if ( $posts_page_id ) {
        $page_content = get_post_field( 'post_content', $posts_page_id );
        if ( ! empty( trim( $page_content ) ) ) {
          echo '<div class="jcp-archive-intro">';
          echo apply_filters( 'the_content', $page_content );
          echo '</div>';
        }
      }
      ?>
    </div>
  </section>

  <!-- Blog Posts Section -->
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
