<?php
/**
 * Post Card Template Part
 * 
 * Displays a post card in archive/listing views.
 *
 * @package JCP_Core
 */
?>

<?php
$categories   = get_the_category();
$category_slugs = ! empty( $categories ) ? array_map( function ( $c ) { return $c->slug; }, $categories ) : [];
$data_categories = implode( ' ', $category_slugs );
$excerpt_20   = wp_trim_words( get_the_excerpt(), 20 );
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
      if ( ! empty( $categories ) ) :
        ?>
        <div class="jcp-post-card-categories">
          <?php
          $category = $categories[0];
          echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="jcp-post-card-category">' . esc_html( $category->name ) . '</a>';
          ?>
        </div>
      <?php endif; ?>

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
      <a href="<?php the_permalink(); ?>" class="jcp-post-card-link">
        <?php esc_html_e( 'Read more →', 'jcp-core' ); ?>
      </a>
    </footer>
  </div>
</article>
