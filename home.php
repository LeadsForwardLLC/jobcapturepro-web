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
  <section class="jcp-section rankings-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?> Blog</h1>
        <?php if ( get_bloginfo( 'description' ) ) : ?>
          <p class="rankings-subtitle"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
        <?php endif; ?>
      </div>

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
