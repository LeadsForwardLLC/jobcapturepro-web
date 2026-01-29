(() => {
  const scriptSrc = document.currentScript && document.currentScript.src ? document.currentScript.src : '';
  const fallbackBase = scriptSrc.includes('/core/')
    ? scriptSrc.split('/core/')[0]
    : '';
  const assetBase = () => window.JCP_ASSET_BASE || fallbackBase;
  const icon = (name) => `${assetBase()}/shared/assets/icons/lucide/${name}.svg`;

  function escAttr(str) {
    if (str == null) return '';
    const s = String(str);
    return s.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }

  function escText(str) {
    if (str == null) return '';
    const s = String(str);
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }

  window.renderEarlyAccess = () => {
    const root = document.getElementById('jcp-app');
    if (!root) return;

    const c = window.JCP_EARLY_ACCESS_FORM || {};
    const options = (c.referral_options || []).map(
      (o) => `<option value="${escAttr(o.value)}">${escText(o.label)}</option>`
    ).join('');

    root.innerHTML = `
      <main class="jcp-marketing jcp-early-access-page">
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

        <section class="jcp-section jcp-form-section">
          <div class="jcp-container">
            <div class="jcp-form-wrapper">
              <form class="jcp-founding-form" id="foundingCrewForm" novalidate>
                <div class="jcp-form-error" id="earlyAccessFormError" role="alert" aria-live="polite" style="display: none;"></div>
                <div class="jcp-form-grid">
                  <div class="jcp-form-field">
                    <label for="founding-name">${escText(c.label_full_name)}</label>
                    <input
                      type="text"
                      id="founding-name"
                      name="first_name"
                      placeholder="${escAttr(c.placeholder_full_name)}"
                      required
                    />
                  </div>
                  <div class="jcp-form-field">
                    <label for="founding-email">${escText(c.label_email)}</label>
                    <input
                      type="email"
                      id="founding-email"
                      name="email"
                      placeholder="${escAttr(c.placeholder_email)}"
                      required
                    />
                  </div>
                  <div class="jcp-form-field">
                    <label for="founding-company">${escText(c.label_company)}</label>
                    <input
                      type="text"
                      id="founding-company"
                      name="company"
                      placeholder="${escAttr(c.placeholder_company)}"
                      required
                    />
                  </div>
                  <div class="jcp-form-field">
                    <label for="founding-phone">${escText(c.label_phone)}</label>
                    <input
                      type="tel"
                      id="founding-phone"
                      name="phone"
                      placeholder="${escAttr(c.placeholder_phone)}"
                      required
                    />
                  </div>
                </div>
                <div class="jcp-form-field jcp-form-field-full">
                  <label for="founding-message">${escText(c.label_message)}</label>
                  <textarea
                    id="founding-message"
                    name="message"
                    placeholder="${escAttr(c.placeholder_message)}"
                    rows="4"
                    required
                  ></textarea>
                </div>
                <div class="jcp-form-field jcp-form-field-full">
                  <label for="founding-referral">${escText(c.label_referral)}</label>
                  <select id="founding-referral" name="referral_source" required>
                    <option value="">${escText(c.placeholder_referral)}</option>
                    ${options}
                  </select>
                </div>
                <div class="jcp-form-field jcp-form-field-full jcp-form-field-consent">
                  <label class="jcp-form-consent-label">
                    <input type="checkbox" name="consent" id="founding-consent" required />
                    <span>${escText(c.label_consent)}</span>
                  </label>
                </div>
                <div class="jcp-form-actions">
                  <button type="submit" class="btn btn-primary" id="earlyAccessSubmitBtn">
                    ${escText(c.submit_button_text)}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </section>

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

    const errorEl = document.getElementById('earlyAccessFormError');
    const submitBtn = document.getElementById('earlyAccessSubmitBtn');

    function showError(msg) {
      if (errorEl) {
        errorEl.textContent = msg;
        errorEl.style.display = 'block';
      }
    }

    function hideError() {
      if (errorEl) {
        errorEl.textContent = '';
        errorEl.style.display = 'none';
      }
    }

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      hideError();

      const consent = form.querySelector('#founding-consent');
      if (!consent || !consent.checked) {
        showError('Please agree to the marketing consent to continue.');
        return;
      }

      const first_name = (form.querySelector('[name="first_name"]') || {}).value || '';
      const company = (form.querySelector('[name="company"]') || {}).value || '';
      const email = (form.querySelector('[name="email"]') || {}).value || '';
      const phone = (form.querySelector('[name="phone"]') || {}).value || '';
      const message = (form.querySelector('[name="message"]') || {}).value || '';
      const referral_source = (form.querySelector('[name="referral_source"]') || {}).value || '';

      if (!first_name.trim() || !company.trim() || !email.trim() || !phone.trim() || !message.trim() || !referral_source) {
        showError('Please fill in all required fields.');
        return;
      }

      if (!submitBtn) return;
      submitBtn.disabled = true;

      const c = window.JCP_EARLY_ACCESS_FORM || {};
      const restUrl = c.rest_url || (window.JCP_CONFIG && window.JCP_CONFIG.baseUrl ? window.JCP_CONFIG.baseUrl.replace(/\/?$/, '') + '/wp-json/jcp/v1/early-access-submit' : '/wp-json/jcp/v1/early-access-submit');

      fetch(restUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          first_name: first_name.trim(),
          company: company.trim(),
          email: email.trim(),
          phone: phone.trim(),
          message: message.trim(),
          referral_source: referral_source.trim(),
        }),
      })
        .then((res) => {
          const ok = res.status === 200 || res.status === 201;
          return res.json().then((data) => ({ ok, data })).catch(() => ({ ok, data: {} }));
        })
        .then(({ ok, data }) => {
          if (ok) {
            const redirect = (window.JCP_EARLY_ACCESS_FORM || {}).success_redirect || '/early-access-success/';
            window.location.href = redirect;
            return;
          }
          submitBtn.disabled = false;
          showError(data.message || 'Something went wrong. Please try again.');
        })
        .catch(() => {
          submitBtn.disabled = false;
          showError('Something went wrong. Please try again.');
        });
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
