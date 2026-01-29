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
        <!-- Simple Hero -->
        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-header">
              <h1>Early Access</h1>
              <p class="rankings-subtitle">
                You're early. That's a good thing. Get access before public launch with early-bird pricing and help shape the platform as it grows.
              </p>
            </div>
          </div>
        </section>

        <!-- Founding Crew Form -->
        <section class="jcp-section jcp-form-section">
          <div class="jcp-container">
            <div class="jcp-form-wrapper">
              <form class="jcp-founding-form" id="foundingCrewForm">
                <div class="jcp-form-grid">
                  <div class="jcp-form-field">
                    <label for="founding-name">Full Name</label>
                    <input 
                      type="text" 
                      id="founding-name" 
                      name="name" 
                      placeholder="John Smith" 
                      required 
                    />
                  </div>
                  <div class="jcp-form-field">
                    <label for="founding-email">Email Address</label>
                    <input 
                      type="email" 
                      id="founding-email" 
                      name="email" 
                      placeholder="john@example.com" 
                      required 
                    />
                  </div>
                  <div class="jcp-form-field">
                    <label for="founding-company">Company Name</label>
                    <input 
                      type="text" 
                      id="founding-company" 
                      name="company" 
                      placeholder="Summit Plumbing" 
                      required 
                    />
                  </div>
                  <div class="jcp-form-field">
                    <label for="founding-phone">Phone Number</label>
                    <input 
                      type="tel" 
                      id="founding-phone" 
                      name="phone" 
                      placeholder="(555) 123-4567" 
                    />
                  </div>
                </div>
                <div class="jcp-form-actions">
                  <button type="submit" class="btn btn-primary">
                    Join Early Access
                  </button>
                  <p class="jcp-form-note">
                    No commitment required. We'll reach out with early-bird pricing details.
                  </p>
                </div>
              </form>
            </div>
          </div>
        </section>

        <!-- Benefits -->
        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-header">
              <h2>Early Access Benefits</h2>
            </div>
            <div class="ranking-factors-grid">
              <div class="ranking-factor-card">
                <div class="factor-icon-wrapper">
                  <img src="${icon('badge-check')}" class="factor-icon" alt="">
                </div>
                <h3 class="factor-title">Early-bird pricing</h3>
                <p class="factor-description">Lock in pricing before public launch. Your rate stays the same as the platform scales.</p>
              </div>
              <div class="ranking-factor-card">
                <div class="factor-icon-wrapper">
                  <img src="${icon('message-square')}" class="factor-icon" alt="">
                </div>
                <h3 class="factor-title">Direct feedback loop</h3>
                <p class="factor-description">Your input shapes the roadmap. Work directly with the team building features you need.</p>
              </div>
              <div class="ranking-factor-card">
                <div class="factor-icon-wrapper">
                  <img src="${icon('zap')}" class="factor-icon" alt="">
                </div>
                <h3 class="factor-title">Priority onboarding</h3>
                <p class="factor-description">Get hands-on setup support so your team can start using the platform quickly.</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Global CTA to Demo -->
        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-cta">
              <div class="cta-content">
                <h3>See how it works</h3>
                <p class="cta-paragraph">Preview how JobCapturePro would publish your work across Google, your website, reviews, and the public directory.</p>
              </div>
              <div class="cta-button-wrapper">
                <a class="btn btn-primary rankings-cta-btn" href="/demo">See your business in the live demo</a>
                <p class="cta-note">No signup required. Takes two minutes.</p>
              </div>
            </div>
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
