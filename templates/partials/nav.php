<?php
/**
 * Global Navigation Partial
 * Used across all pages - links are dynamic based on current page
 *
 * @package JCP_Core
 */
$pages = jcp_core_get_page_detection();
$is_directory = $pages['is_directory'];
$is_company = $pages['is_company'];
$is_demo = $pages['is_demo'];
?><header class="directory-header" id="jcpGlobalHeader">
  <div class="header-brand">
    <a href="/" class="brand-link">
      <img
        src="https://jobcapturepro.com/wp-content/uploads/2025/11/JobCapturePro-Logo-Dark.png"
        alt="JobCapturePro"
        class="logo-image"
      />
    </a>
    <span class="demo-indicator is-hidden" id="jcpHeaderIndicator"><?php echo $is_company ? 'Contractor Profile' : ( $is_directory ? 'Directory' : ( $is_demo ? 'Interactive Demo' : 'Live Demo' ) ); ?></span>
  </div>

  <nav class="header-nav" id="headerNav">
    <?php if ( $is_demo ) : ?>
      <a href="#how-it-works" class="nav-link" data-home-anchor="#how-it-works">How it works</a>
      <a href="#features" class="nav-link" data-home-anchor="#features">Features</a>
      <a href="#who-its-for" class="nav-link" data-home-anchor="#who-its-for">Who it's for</a>
      <a href="/pricing" class="nav-link" data-page="pricing">Pricing</a>
    <?php elseif ( $is_company ) : ?>
      <a href="/directory" class="nav-link" data-page="directory">Back to Directory</a>
      <a href="/directory#how-it-works" class="nav-link" data-page="directory">How rankings work</a>
      <a href="/pricing" class="nav-link" data-page="pricing">Pricing</a>
    <?php elseif ( $is_directory ) : ?>
      <a href="#how-it-works" class="nav-link" data-home-anchor="#how-it-works">How rankings work</a>
      <a href="/pricing" class="nav-link" data-page="pricing">Pricing</a>
      <a href="/early-access" class="nav-link" data-page="early-access">Early Access</a>
    <?php else : ?>
      <a href="#how-it-works" class="nav-link" data-home-anchor="#how-it-works">How it works</a>
      <a href="#features" class="nav-link" data-home-anchor="#features">Features</a>
      <a href="#who-its-for" class="nav-link" data-home-anchor="#who-its-for">Who it's for</a>
      <a href="/pricing" class="nav-link" data-page="pricing">Pricing</a>
    <?php endif; ?>
  </nav>

  <div class="header-actions">
    <?php if ( $is_demo ) : ?>
      <button class="btn btn-secondary" id="btnReset">↺ Reset</button>
      <button class="btn btn-secondary is-hidden" id="btnViewDirectory" type="button">View Demo Directory →</button>
      <button class="btn btn-primary" id="btnNext">Run Guided Demo →</button>
    <?php elseif ( $is_company ) : ?>
      <a href="/directory" class="btn btn-secondary" id="dynamicBackBtn">
        <svg id="dynamicBackIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        <span>Back to Directory</span>
      </a>
      <a href="/early-access" class="btn btn-primary" target="_blank" rel="noopener">Get Listed</a>
    <?php else : ?>
      <a href="/demo" class="btn btn-secondary" id="dynamicBackBtn">
        <span>Online Demo</span>
        <svg id="dynamicBackIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M5 12h14M13 5l7 7-7 7"/>
        </svg>
      </a>
      <a href="/early-access" class="btn btn-primary" target="_blank" rel="noopener"><?php echo $is_directory ? 'Get Listed' : 'Get Started'; ?></a>
    <?php endif; ?>
  </div>

  <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
    <span class="menu-icon">
      <span></span>
      <span></span>
      <span></span>
    </span>
  </button>
</header>

<div class="mobile-menu-overlay" id="mobileMenuOverlay">
  <div class="mobile-menu-content">
    <div class="mobile-menu-header">
      <div class="mobile-menu-title">
        <img
          src="https://jobcapturepro.com/wp-content/uploads/2025/11/JobCapturePro-Logo-Dark.png"
          alt="JobCapturePro"
          class="mobile-logo"
        />
        <span class="mobile-directory-badge is-hidden" id="jcpMobileBadge"><?php echo $is_company ? 'Contractor Profile' : ( $is_directory ? 'Directory' : ( $is_demo ? 'Interactive Demo' : 'Live Demo' ) ); ?></span>
      </div>
      <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Close menu">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>

    <nav class="mobile-nav" id="mobileNav">
      <?php if ( $is_demo ) : ?>
        <a href="#how-it-works" class="mobile-nav-link" data-home-anchor="#how-it-works">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polygon points="10 8 16 12 10 16 10 8"></polygon>
          </svg>
          <span>How it works</span>
        </a>
        <a href="#features" class="mobile-nav-link" data-home-anchor="#features">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
          </svg>
          <span>Features</span>
        </a>
        <a href="#who-its-for" class="mobile-nav-link" data-home-anchor="#who-its-for">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
          <span>Who it's for</span>
        </a>
        <a href="/pricing" class="mobile-nav-link" data-page="pricing">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="2" x2="12" y2="22"></line>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
          </svg>
          <span>Pricing</span>
        </a>
      <?php elseif ( $is_company ) : ?>
        <a href="/directory" class="mobile-nav-link" data-page="directory">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          <span>Back to Directory</span>
        </a>
        <a href="/directory#how-it-works" class="mobile-nav-link" data-page="directory">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polygon points="10 8 16 12 10 16 10 8"></polygon>
          </svg>
          <span>How rankings work</span>
        </a>
        <a href="/pricing" class="mobile-nav-link" data-page="pricing">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="2" x2="12" y2="22"></line>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
          </svg>
          <span>Pricing</span>
        </a>
      <?php elseif ( $is_directory ) : ?>
        <a href="#how-it-works" class="mobile-nav-link" data-home-anchor="#how-it-works">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polygon points="10 8 16 12 10 16 10 8"></polygon>
          </svg>
          <span>How rankings work</span>
        </a>
        <a href="/pricing" class="mobile-nav-link" data-page="pricing">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="2" x2="12" y2="22"></line>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
          </svg>
          <span>Pricing</span>
        </a>
        <a href="/early-access" class="mobile-nav-link" data-page="early-access">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
          </svg>
          <span>Early Access</span>
        </a>
      <?php else : ?>
        <a href="#how-it-works" class="mobile-nav-link" data-home-anchor="#how-it-works">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polygon points="10 8 16 12 10 16 10 8"></polygon>
          </svg>
          <span>How it works</span>
        </a>
        <a href="#features" class="mobile-nav-link" data-home-anchor="#features">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
          </svg>
          <span>Features</span>
        </a>
        <a href="#who-its-for" class="mobile-nav-link" data-home-anchor="#who-its-for">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
          <span>Who it's for</span>
        </a>
        <a href="/pricing" class="mobile-nav-link" data-page="pricing">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="2" x2="12" y2="22"></line>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
          </svg>
          <span>Pricing</span>
        </a>
      <?php endif; ?>
    </nav>

    <div class="mobile-menu-actions">
      <?php if ( $is_demo ) : ?>
        <button class="mobile-btn mobile-btn-secondary" id="mobileBtnReset">↺ Reset</button>
        <button class="mobile-btn mobile-btn-primary" id="mobileBtnNext">Run Guided Demo →</button>
      <?php elseif ( $is_company ) : ?>
        <a href="/directory" class="mobile-btn mobile-btn-secondary" id="mobileDynamicBackBtn">
          <svg id="mobileDynamicBackIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          <span>Back to Directory</span>
        </a>
        <a href="/early-access" class="mobile-btn mobile-btn-primary" target="_blank" rel="noopener">Get Listed</a>
      <?php else : ?>
        <a href="/demo" class="mobile-btn mobile-btn-secondary" id="mobileDynamicBackBtn">
          <span>Online Demo</span>
          <svg id="mobileDynamicBackIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M13 5l7 7-7 7"/>
          </svg>
        </a>
        <a href="/early-access" class="mobile-btn mobile-btn-primary" target="_blank" rel="noopener"><?php echo $is_directory ? 'Get Listed' : 'Get Started'; ?></a>
      <?php endif; ?>
    </div>
  </div>
</div>
