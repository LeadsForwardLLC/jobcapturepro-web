<?php
/**
 * Empty State Template Part
 * 
 * Displays when no posts are found.
 *
 * @package JCP_Core
 */
?>

<div class="jcp-empty-state">
  <h2><?php esc_html_e( 'Nothing Found', 'jcp-core' ); ?></h2>
  <p>
    <?php
    if ( is_search() ) {
      esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'jcp-core' );
    } else {
      esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'jcp-core' );
    }
    ?>
  </p>
  <?php get_search_form(); ?>
</div>
