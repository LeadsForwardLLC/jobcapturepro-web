<?php
/**
 * Help Article Card Template Part
 *
 * Displays a help article card in the Help Articles archive.
 * Uses taxonomy "help-category" for categories and data-categories (filter bar).
 *
 * @package JCP_Core
 */
$help_tax = 'help-category';
$terms    = get_the_terms( get_the_ID(), $help_tax );
$slugs    = [];
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
    $slugs = array_map( function ( $t ) {
        return $t->slug;
    }, $terms );
}
$data_categories = implode( ' ', $slugs );
$excerpt_20      = wp_trim_words( get_the_excerpt(), 20 );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'jcp-card jcp-post-card' ); ?> data-categories="<?php echo esc_attr( $data_categories ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>" data-excerpt="<?php echo esc_attr( $excerpt_20 ); ?>" data-date="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
  <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php echo esc_url( get_permalink() ); ?>" class="jcp-post-card-image">
      <?php the_post_thumbnail( 'medium_large', [ 'alt' => get_the_title() ] ); ?>
    </a>
  <?php endif; ?>

  <div class="jcp-post-card-content">
    <header class="jcp-post-card-header">
      <?php
      if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
        $term = $terms[0];
        $term_link = get_term_link( $term, $help_tax );
        if ( ! is_wp_error( $term_link ) ) :
          ?>
        <div class="jcp-post-card-categories">
          <a href="<?php echo esc_url( $term_link ); ?>" class="jcp-post-card-category"><?php echo esc_html( $term->name ); ?></a>
        </div>
          <?php
        endif;
      endif;
      ?>
      <h2 class="jcp-post-card-title">
        <a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
      </h2>
    </header>

    <div class="jcp-post-card-excerpt">
      <?php echo esc_html( $excerpt_20 ); ?>
    </div>

    <footer class="jcp-post-card-footer">
      <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="jcp-post-card-date">
        <?php echo esc_html( get_the_date() ); ?>
      </time>
      <a href="<?php echo esc_url( get_permalink() ); ?>" class="jcp-post-card-link">
        <?php esc_html_e( 'Read more →', 'jcp-core' ); ?>
      </a>
    </footer>
  </div>
</article>
