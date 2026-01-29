<?php
/**
 * Pagination Template Part
 * 
 * Displays pagination for archive pages.
 *
 * @package JCP_Core
 */

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
  return;
}
?>

<nav class="jcp-pagination" aria-label="<?php esc_attr_e( 'Posts navigation', 'jcp-core' ); ?>">
  <div class="jcp-pagination-links">
    <?php
    echo paginate_links( [
      'prev_text' => '<span aria-hidden="true">←</span> <span class="screen-reader-text">' . esc_html__( 'Previous', 'jcp-core' ) . '</span>',
      'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'jcp-core' ) . '</span> <span aria-hidden="true">→</span>',
      'type'      => 'list',
      'end_size' => 2,
      'mid_size' => 1,
    ] );
    ?>
  </div>
</nav>
