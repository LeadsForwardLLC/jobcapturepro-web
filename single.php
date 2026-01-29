<?php
/**
 * Single Post Template
 * 
 * Displays individual blog posts.
 *
 * @package JCP_Core
 */

get_header();
?>

<main class="jcp-marketing">
  <?php
  while ( have_posts() ) :
    the_post();
    ?>
  <section class="jcp-section rankings-section">
    <div class="jcp-container">
      <div class="rankings-header">
        <h1><?php the_title(); ?></h1>
        <div class="jcp-post-meta">
          <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="jcp-post-meta-author" rel="author">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 36, '', get_the_author(), [ 'class' => 'jcp-post-meta-avatar' ] ); ?>
            <span class="jcp-post-meta-author-name"><?php the_author(); ?></span>
          </a>
          <span class="jcp-post-meta-sep" aria-hidden="true">·</span>
          <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="jcp-post-date">
            <?php echo esc_html( get_the_date() ); ?>
          </time>
          <?php
          $categories = get_the_category();
          if ( ! empty( $categories ) ) :
            ?>
            <span class="jcp-post-meta-sep" aria-hidden="true">·</span>
            <span class="jcp-post-categories">
              <?php
              foreach ( $categories as $category ) {
                echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="jcp-post-category">' . esc_html( $category->name ) . '</a>';
              }
              ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <div class="jcp-single-post-wrapper">
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'jcp-single-post' ); ?>>
          <div class="jcp-post-content">
            <?php
            the_content();

            wp_link_pages( [
              'before' => '<nav class="jcp-page-links"><p class="jcp-page-links-title">' . esc_html__( 'Pages:', 'jcp-core' ) . '</p>',
              'after'  => '</nav>',
            ] );
            ?>
          </div>

          <?php
          $tags = get_the_tags();
          if ( $tags ) :
            ?>
            <footer class="jcp-post-footer">
              <div class="jcp-post-tags">
                <span class="jcp-post-tags-label"><?php esc_html_e( 'Tags:', 'jcp-core' ); ?></span>
                <?php
                foreach ( $tags as $tag ) {
                  echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="jcp-post-tag">' . esc_html( $tag->name ) . '</a>';
                }
                ?>
              </div>
            </footer>
          <?php endif; ?>
        </article>

        <nav class="jcp-post-navigation">
          <?php
          $prev_post = get_previous_post();
          $next_post = get_next_post();
          ?>
          <?php if ( $prev_post ) : ?>
            <div class="jcp-post-nav-prev">
              <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="btn btn-secondary">
                ← Previous: <?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
              </a>
            </div>
          <?php endif; ?>
          <?php if ( $next_post ) : ?>
            <div class="jcp-post-nav-next">
              <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="btn btn-secondary">
                Next: <?php echo esc_html( get_the_title( $next_post->ID ) ); ?> →
              </a>
            </div>
          <?php endif; ?>
        </nav>

        <?php
        if ( comments_open() || get_comments_number() ) {
          comments_template();
        }
        ?>
      </div>
    </div>
  </section>
  <?php endwhile; ?>
</main>

<?php
get_footer();
