<?php
/**
 * Global Footer Template
 * Renders the global footer and closing body/html tags
 *
 * @package JCP_Core
 */

$privacy_url = 'https://jobcapturepro.com/privacy-policy/';
$terms_url  = 'https://jobcapturepro.com/terms-and-conditions/';
$leadsforward_url = 'https://leadsforward.com/';
$social_links = [
    'facebook'  => [ 'url' => 'https://www.facebook.com/profile.php?id=61574958638999', 'label' => 'Facebook' ],
    'x'        => [ 'url' => 'https://x.com/jobcapturepro', 'label' => 'X' ],
    'instagram'=> [ 'url' => 'https://www.instagram.com/jobcapturepro/', 'label' => 'Instagram' ],
    'tiktok'   => [ 'url' => 'https://www.tiktok.com/@jobcapturepro', 'label' => 'TikTok' ],
];
?>
  <footer class="jcp-footer">
    <div class="jcp-container jcp-footer-grid">
      <div class="jcp-footer-brand">
        <img src="<?php echo esc_url( 'https://jobcapturepro.com/wp-content/uploads/2025/11/JobCapturePro-Logo-Dark.png' ); ?>" alt="<?php esc_attr_e( 'JobCapturePro', 'jcp-core' ); ?>" />
        <p>Turn real job photos into proof, visibility, reviews, and more jobs.</p>
      </div>
      <div class="jcp-footer-col">
        <h4>Product</h4>
        <a href="<?php echo esc_url( home_url( '/demo' ) ); ?>">Live demo</a>
        <a href="<?php echo esc_url( home_url( '/directory' ) ); ?>">Directory</a>
      </div>
      <div class="jcp-footer-col">
        <h4>Company</h4>
        <a href="<?php echo esc_url( home_url( '/pricing' ) ); ?>">Pricing</a>
        <a href="<?php echo esc_url( home_url( '/early-access' ) ); ?>">Founding crew</a>
        <a href="<?php echo esc_url( home_url( '/#how-it-works' ) ); ?>">How it works</a>
      </div>
    </div>

    <div class="jcp-footer-bottom">
      <div class="jcp-container jcp-footer-bottom-inner">
        <nav class="jcp-footer-legal" aria-label="<?php esc_attr_e( 'Legal', 'jcp-core' ); ?>">
          <a href="<?php echo esc_url( $privacy_url ); ?>"><?php esc_html_e( 'Privacy', 'jcp-core' ); ?></a>
          <span class="jcp-footer-sep" aria-hidden="true">·</span>
          <a href="<?php echo esc_url( $terms_url ); ?>"><?php esc_html_e( 'Terms', 'jcp-core' ); ?></a>
        </nav>
        <div class="jcp-footer-social" role="list">
          <?php foreach ( $social_links as $key => $item ) : ?>
            <a href="<?php echo esc_url( $item['url'] ); ?>" class="jcp-footer-social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $item['label'] ); ?>" role="listitem">
              <?php if ( $key === 'facebook' ) : ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              <?php elseif ( $key === 'x' ) : ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              <?php elseif ( $key === 'instagram' ) : ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.265.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.058 1.645-.07 4.849-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              <?php elseif ( $key === 'tiktok' ) : ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
              <?php endif; ?>
            </a>
          <?php endforeach; ?>
        </div>
        <p class="jcp-footer-powered">
          <?php esc_html_e( 'Powered by', 'jcp-core' ); ?>
          <a href="<?php echo esc_url( $leadsforward_url ); ?>" target="_blank" rel="noopener noreferrer">LeadsForward</a>
        </p>
      </div>
    </div>
  </footer>
  </div><!-- .jcp-shell -->
  <?php wp_footer(); ?>
</body>
</html>
