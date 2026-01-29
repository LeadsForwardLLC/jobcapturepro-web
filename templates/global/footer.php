<?php
/**
 * Global Footer Template
 * Renders the global footer and closing body/html tags
 *
 * @package JCP_Core
 */

// Directory and company profile pages get their own footer, not global footer
$pages = jcp_core_get_page_detection();
if ( $pages['is_directory'] || $pages['is_company'] ) {
    // Directory/company footer is handled in the HTML template
    echo '</div><!-- .jcp-shell -->';
    wp_footer();
    echo '</body></html>';
    return;
}
?>
  <footer class="jcp-footer">
    <div class="jcp-container jcp-footer-grid">
      <div class="jcp-footer-brand">
        <img src="<?php echo esc_url( 'https://jobcapturepro.com/wp-content/uploads/2025/11/JobCapturePro-Logo-Dark.png' ); ?>" alt="<?php esc_attr_e( 'JobCapturePro', 'jcp-core' ); ?>" />
        <p>Turn real job photos into proof, visibility, reviews, and more jobs.</p>
      </div>
      <div class="jcp-footer-col">
        <h4>Product</h4>
        <a href="/demo">Live demo</a>
        <a href="/directory">Directory</a>
      </div>
      <div class="jcp-footer-col">
        <h4>Company</h4>
        <a href="/pricing">Pricing</a>
        <a href="/early-access">Founding crew</a>
        <a href="/#how-it-works">How it works</a>
      </div>
    </div>
  </footer>
  </div><!-- .jcp-shell -->
  <?php wp_footer(); ?>
</body>
</html>
