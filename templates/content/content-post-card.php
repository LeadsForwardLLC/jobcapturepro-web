<?php
/**
 * Post Card Template Part
 * 
 * Displays a post card in archive/listing views.
 *
 * @package JCP_Core
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'jcp-card jcp-post-card' ); ?>>
  <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" class="jcp-post-card-image">
      <?php the_post_thumbnail( 'medium_large', [ 'alt' => get_the_title() ] ); ?>
    </a>
  <?php endif; ?>

  <div class="jcp-post-card-content">
    <header class="jcp-post-card-header">
      <?php
      $categories = get_the_category();
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
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>
    </header>

    <div class="jcp-post-card-excerpt">
      <?php the_excerpt(); ?>
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
