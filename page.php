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
  <section class="jcp-section">
    <div class="jcp-container">
      <?php
      while ( have_posts() ) :
        the_post();
        ?>
      <div class="rankings-header">
        <h1><?php the_title(); ?></h1>
      </div>
      <div class="jcp-page-content">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <div class="jcp-page-body">
            <?php
            the_content();

            wp_link_pages( [
              'before' => '<nav class="jcp-page-links"><p class="jcp-page-links-title">' . esc_html__( 'Pages:', 'jcp-core' ) . '</p>',
              'after'  => '</nav>',
            ] );
            ?>
          </div>
        </article>
      </div>
      <?php
      endwhile;
      ?>
    </div>
  </section>
</main>

<?php
get_footer();
