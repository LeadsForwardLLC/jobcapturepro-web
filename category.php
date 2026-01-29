<?php
/**
 * Category Archive Template
 * 
 * Displays category archive pages.
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
        <h1><?php single_cat_title(); ?></h1>
        <?php
        $description = category_description();
        if ( $description ) :
          ?>
          <p class="rankings-subtitle"><?php echo wp_kses_post( $description ); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Category Posts Section -->
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
