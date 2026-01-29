(() => {
  const scriptSrc = document.currentScript && document.currentScript.src ? document.currentScript.src : '';
  const fallbackBase = scriptSrc.includes('/core/')
    ? scriptSrc.split('/core/')[0]
    : '';
  const assetBase = () => window.JCP_ASSET_BASE || fallbackBase;
  const icon = (name) => `${assetBase()}/shared/assets/icons/lucide/${name}.svg`;

  window.renderEarlyAccess = () => {
    const root = document.getElementById('jcp-app');
    if (!root) return;

    root.innerHTML = `
      <main class="jcp-marketing jcp-early-access-page">
        <!-- Hero Section -->
        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-header">
              <h1>Early access to JobCapturePro</h1>
              <p class="rankings-subtitle">For teams ready to operationalize proof-of-work.</p>
            </div>
          </div>
        </section>

        <!-- What Early Access Includes -->
        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-header">
              <h2>What early access includes</h2>
            </div>
            <div class="jcp-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--jcp-space-xl); max-width: 900px; margin: 0 auto;">
              <div class="jcp-card">
                <h3>Full platform access</h3>
                <p>Complete access to all features and channels as they're released.</p>
              </div>
              <div class="jcp-card">
                <h3>Priority onboarding</h3>
                <p>Dedicated setup support to get your team operational quickly.</p>
              </div>
              <div class="jcp-card">
                <h3>Direct feedback loop</h3>
                <p>Share input directly with the product team building the platform.</p>
              </div>
              <div class="jcp-card">
                <h3>Early feature rollout</h3>
                <p>Access to new features and improvements as they're released.</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Who It's For / Not For -->
        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-header">
              <h2>Is this a fit?</h2>
            </div>
            <div class="jcp-grid" style="grid-template-columns: repeat(2, 1fr); gap: var(--jcp-space-4xl); max-width: 1000px; margin: 0 auto;">
              <div>
                <h3 style="font-size: var(--jcp-font-size-xl); font-weight: var(--jcp-font-weight-bold); margin-bottom: var(--jcp-space-lg); color: var(--jcp-color-text-primary);">Good fit if:</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: var(--jcp-space-md);">
                  <li style="display: flex; align-items: flex-start; gap: var(--jcp-space-sm);">
                    <span style="color: var(--jcp-color-primary); font-weight: var(--jcp-font-weight-bold);">✓</span>
                    <span>Actively running jobs</span>
                  </li>
                  <li style="display: flex; align-items: flex-start; gap: var(--jcp-space-sm);">
                    <span style="color: var(--jcp-color-primary); font-weight: var(--jcp-font-weight-bold);">✓</span>
                    <span>Wants clean proof-of-work</span>
                  </li>
                  <li style="display: flex; align-items: flex-start; gap: var(--jcp-space-sm);">
                    <span style="color: var(--jcp-color-primary); font-weight: var(--jcp-font-weight-bold);">✓</span>
                    <span>Values systems over hacks</span>
                  </li>
                </ul>
              </div>
              <div>
                <h3 style="font-size: var(--jcp-font-size-xl); font-weight: var(--jcp-font-weight-bold); margin-bottom: var(--jcp-space-lg); color: var(--jcp-color-text-primary);">Not a fit if:</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: var(--jcp-space-md);">
                  <li style="display: flex; align-items: flex-start; gap: var(--jcp-space-sm);">
                    <span style="color: var(--jcp-color-text-tertiary); font-weight: var(--jcp-font-weight-bold);">×</span>
                    <span>Just exploring</span>
                  </li>
                  <li style="display: flex; align-items: flex-start; gap: var(--jcp-space-sm);">
                    <span style="color: var(--jcp-color-text-tertiary); font-weight: var(--jcp-font-weight-bold);">×</span>
                    <span>Wants DIY marketing tools</span>
                  </li>
                  <li style="display: flex; align-items: flex-start; gap: var(--jcp-space-sm);">
                    <span style="color: var(--jcp-color-text-tertiary); font-weight: var(--jcp-font-weight-bold);">×</span>
                    <span>Not ready to implement</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </section>

        <!-- Primary CTA Block -->
        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-cta">
              <div class="cta-content">
                <h3>Request early access</h3>
                <p class="cta-paragraph">Join teams operationalizing proof-of-work.</p>
              </div>
              <div class="cta-button-wrapper">
                <form class="jcp-founding-form" id="foundingCrewForm" style="display: flex; flex-direction: column; gap: var(--jcp-space-md); min-width: 280px;">
                  <input 
                    type="text" 
                    id="founding-name" 
                    name="name" 
                    placeholder="Full name" 
                    required 
                    style="padding: var(--jcp-space-md); border-radius: var(--jcp-radius-md); border: 2px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.1); color: var(--jcp-color-text-light); font-size: var(--jcp-font-size-base); font-family: var(--jcp-font-family-base);"
                  />
                  <input 
                    type="email" 
                    id="founding-email" 
                    name="email" 
                    placeholder="Email address" 
                    required 
                    style="padding: var(--jcp-space-md); border-radius: var(--jcp-radius-md); border: 2px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.1); color: var(--jcp-color-text-light); font-size: var(--jcp-font-size-base); font-family: var(--jcp-font-family-base);"
                  />
                  <input 
                    type="text" 
                    id="founding-company" 
                    name="company" 
                    placeholder="Company name" 
                    required 
                    style="padding: var(--jcp-space-md); border-radius: var(--jcp-radius-md); border: 2px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.1); color: var(--jcp-color-text-light); font-size: var(--jcp-font-size-base); font-family: var(--jcp-font-family-base);"
                  />
                  <button type="submit" class="btn rankings-cta-btn" style="margin-top: var(--jcp-space-sm);">
                    Request access
                  </button>
                </form>
              </div>
            </div>
            <p class="cta-note" style="margin-top: var(--jcp-space-lg); text-align: center; max-width: 600px; margin-left: auto; margin-right: auto;">
              No obligation. Limited access. We'll reach out with next steps.
            </p>
          </div>
        </section>
      </main>
    `;

    initMarketingNav();
    initFoundingForm();
  };

  function initFoundingForm() {
    const form = document.getElementById('foundingCrewForm');
    if (!form) return;

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const formData = new FormData(form);
      const data = Object.fromEntries(formData);
      
      // TODO: Replace with actual form submission endpoint
      console.log('Form submission:', data);
      
      // Show success message
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.textContent = 'Submitted! We\'ll be in touch soon.';
      submitBtn.disabled = true;
      
      // Reset form after 3 seconds
      setTimeout(() => {
        form.reset();
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      }, 3000);
    });
  }

  function initMarketingNav() {
    const menuToggle = document.getElementById('mobileMenuToggle');
    const menuClose = document.getElementById('mobileMenuClose');
    const menuOverlay = document.getElementById('mobileMenuOverlay');

    if (!menuToggle || !menuClose || !menuOverlay) return;

    menuToggle.addEventListener('click', () => {
      menuOverlay.classList.add('active');
      menuToggle.classList.add('active');
      document.body.style.overflow = 'hidden';
    });

    const closeMenu = () => {
      menuOverlay.classList.remove('active');
      menuToggle.classList.remove('active');
      document.body.style.overflow = '';
    };

    menuClose.addEventListener('click', closeMenu);
    menuOverlay.addEventListener('click', (e) => {
      if (e.target === menuOverlay) {
        closeMenu();
      }
    });

    document.querySelectorAll('.mobile-nav-link').forEach((link) => {
      link.addEventListener('click', () => closeMenu());
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && menuOverlay.classList.contains('active')) {
        closeMenu();
      }
    });
  }
})();
