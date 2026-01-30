(() => {
  const scriptSrc = document.currentScript && document.currentScript.src ? document.currentScript.src : '';
  const fallbackBase = scriptSrc.includes('/core/')
    ? scriptSrc.split('/core/')[0]
    : '';
  const assetBase = () => window.JCP_ASSET_BASE || fallbackBase;

  const icon = (name) => `${assetBase()}/shared/assets/icons/lucide/${name}.svg`;

  const defaultCtas = {
    primaryText: 'View the live demo',
    primaryUrl: '/demo',
    secondaryText: 'Learn how it works',
    secondaryUrl: '#how-it-works',
  };

  const useDefault = (val, defaultVal) => (val != null && String(val).trim() !== '' ? String(val).trim() : defaultVal);

  const getHeroCtas = () => {
    const c = typeof window.JCP_HOME_HERO_CTAS !== 'undefined' ? window.JCP_HOME_HERO_CTAS : {};
    return {
      primaryText: useDefault(c.primary_text, defaultCtas.primaryText),
      primaryUrl: useDefault(c.primary_url, defaultCtas.primaryUrl),
      secondaryText: useDefault(c.secondary_text, defaultCtas.secondaryText),
      secondaryUrl: useDefault(c.secondary_url, defaultCtas.secondaryUrl),
    };
  };

  window.renderHome = () => {
    const root = document.getElementById('jcp-app');
    if (!root) return;

    const heroCtas = getHeroCtas();

    root.innerHTML = `
      <main class="jcp-marketing jcp-home">
        
        <!-- ============================================================
             HERO SECTION
             ============================================================ -->
        <section class="jcp-section jcp-hero">
          <div class="jcp-container">
            <div class="jcp-hero-grid">
              <div class="jcp-hero-copy hero-copy">
                <h1 class="jcp-hero-title">Automatically turn every completed job into <span class="jcp-hero-title-end">more <span class="jcp-hero-rotating-word" aria-live="polite">visibility</span></span></h1>
                <p class="jcp-hero-subtitle">
                  Your team already takes job photos. JobCapturePro automatically turns them into Google updates, website content, social media posts, and review requests so your work keeps marketing itself.
                </p>
                <div class="jcp-actions directory-cta-row">
                  <a class="btn btn-primary" href="${heroCtas.primaryUrl}">${heroCtas.primaryText}</a>
                  <a class="btn btn-secondary" href="${heroCtas.secondaryUrl}">${heroCtas.secondaryText}</a>
                </div>
                <div class="directory-meta">
                  <div class="meta-item meta-stat-photo">
                    <div class="meta-label">
                      <img src="${icon('camera')}" class="meta-icon" alt="">
                      <strong>1 photo</strong>
                    </div>
                    <span>proof everywhere</span>
                  </div>
                  <div class="meta-item meta-stat-channels">
                    <div class="meta-label">
                      <img src="${icon('map')}" class="meta-icon" alt="">
                      <strong>4 channels</strong>
                    </div>
                    <span>shared on website + social</span>
                  </div>
                  <div class="meta-item meta-stat-busywork">
                    <div class="meta-label">
                      <img src="${icon('clock')}" class="meta-icon" alt="">
                      <strong>0 busywork</strong>
                    </div>
                    <span>no extra work from you</span>
                  </div>
                </div>
              </div>

              <div class="jcp-hero-visual hero-visual">
                <div class="hero-visual-stack">
                  <div class="hero-visual-lines" aria-hidden="true">
                    <span class="hero-line hero-line-1"></span>
                    <span class="hero-line hero-line-2"></span>
                    <span class="hero-line hero-line-3"></span>
                    <span class="hero-line hero-line-4"></span>
                    <span class="hero-line hero-line-5"></span>
                  </div>
                  <a href="/demo" class="demo-phone-mockup hero-phone-mockup">
                  <div class="phone-frame hero-phone-frame">
                    <div class="phone-screen">
                      <div class="phone-content">
                        <div class="phone-header hero-phone-header">
                          <div class="phone-status-bar">
                            <span>9:41</span>
                            <svg class="phone-battery-icon" width="24" height="12" viewBox="0 0 24 12" fill="none" stroke="currentColor" stroke-width="1.5">
                              <rect x="1" y="3" width="18" height="6" rx="1.5" fill="currentColor" fill-opacity="1"/>
                              <rect x="1" y="3" width="18" height="6" rx="1.5" stroke="currentColor"/>
                              <path d="M20 5v2h2v-2z" fill="currentColor"/>
                            </svg>
                          </div>
                          <div class="hero-phone-live-row">
                            <span class="hero-phone-live-badge">Live</span>
                          </div>
                        </div>
                        <div class="phone-body hero-phone-body">
                          <div class="hero-phone-image-wrap">
                            <img src="http://jobcapturepro.com/wp-content/uploads/2025/12/jcp-user-photo.jpg" alt="Job photo" class="hero-phone-image" />
                          </div>
                          <div class="demo-preview-item hero-phone-card hero-phone-card-1">
                            <div class="demo-item-icon">
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                              </svg>
                            </div>
                            <div class="demo-item-content">
                              <div class="demo-item-title">New job captured</div>
                              <div class="demo-item-subtitle">Photo uploaded</div>
                            </div>
                          </div>
                          <div class="demo-preview-item hero-phone-card hero-phone-card-2">
                            <div class="demo-item-icon">
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                              </svg>
                            </div>
                            <div class="demo-item-content">
                              <div class="demo-item-title">AI check-in complete</div>
                              <div class="demo-item-subtitle">Verified proof ready</div>
                            </div>
                          </div>
                          <div class="demo-preview-item hero-phone-card hero-phone-card-3">
                            <div class="demo-item-icon">
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                              </svg>
                            </div>
                            <div class="demo-item-content">
                              <div class="demo-item-title">Published everywhere</div>
                              <div class="demo-item-subtitle">Google Maps • Website • Directory</div>
                            </div>
                          </div>
                        </div>
                        <div class="phone-click-hint hero-phone-cta">
                          <span>Try the demo</span>
                          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M13 5l7 7-7 7"/>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ============================================================
             HOW IT WORKS SECTION
             ============================================================ -->
        <section class="jcp-section rankings-section" id="how-it-works">
          <div class="jcp-container">
            <div class="rankings-header">
              <h2>How JobCapturePro works</h2>
              <p class="rankings-subtitle">
                Every completed job becomes verified proof across every channel that matters. Here's the simple flow your crew already knows.
              </p>
            </div>

            <div class="timeline-steps">
              <div class="timeline-step">
                <div class="step-number">1</div>
                <div class="step-content">
                  <h4 class="step-title">Capture</h4>
                  <p class="step-description">Crew snaps a photo or the job completes in your CRM.</p>
                </div>
              </div>
              <div class="timeline-step">
                <div class="step-number">2</div>
                <div class="step-content">
                  <h4 class="step-title">AI Check-In</h4>
                  <p class="step-description">JobCapturePro generates the full check-in automatically.</p>
                </div>
              </div>
              <div class="timeline-step">
                <div class="step-number">3</div>
                <div class="step-content">
                  <h4 class="step-title">Publish</h4>
                  <p class="step-description">Website, directory, GBP, and social update instantly.</p>
                </div>
              </div>
              <div class="timeline-step">
                <div class="step-number">4</div>
                <div class="step-content">
                  <h4 class="step-title">Review</h4>
                  <p class="step-description">Smart review requests go out at the right moment.</p>
                </div>
              </div>
            </div>
            <div class="timeline-cta">
              <a href="#demo-preview" class="timeline-cta-link">
                See the demo version of this flow
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M5 12h14M13 5l7 7-7 7"/>
                </svg>
              </a>
            </div>

            <div class="demo-preview-section" id="demo-preview">
              <div class="demo-preview-card">
                <div class="demo-preview-content">
                  <div class="demo-preview-text">
                    <div class="demo-badge">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polygon points="10 8 16 12 10 16 10 8"/>
                      </svg>
                      <span>Live Demo</span>
                    </div>
                    <h3 class="demo-preview-title">See it in action</h3>
                    <p class="demo-preview-description">
                      Watch how JobCapturePro turns a single job photo into verified proof across Google Maps, your website, directory listings, and review requests — all automatically.
                    </p>
                    <p class="demo-preview-cue">
                      You will see how one job becomes Google updates, website proof, directory presence, and review requests.
                    </p>
                    <div class="demo-cta-wrapper">
                      <a href="/demo" class="btn btn-primary demo-cta-primary">
                        <span>Launch Interactive Demo</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M5 12h14M13 5l7 7-7 7"/>
                        </svg>
                      </a>
                      <p class="demo-cta-note">No signup required • Takes 2 minutes</p>
                    </div>
                  </div>
                  <div class="demo-preview-visual">
                    <a href="/demo" class="demo-phone-mockup">
                      <div class="phone-frame">
                        <div class="phone-screen demo-phone-screen">
                          <div class="phone-content demo-phone-content">
                            <div class="phone-header">
                              <div class="phone-status-bar">
                                <span>9:41</span>
                                <svg class="phone-battery-icon" width="24" height="12" viewBox="0 0 24 12" fill="none" stroke="currentColor" stroke-width="1.5">
                                  <rect x="1" y="3" width="18" height="6" rx="1.5" fill="currentColor" fill-opacity="1"/>
                                  <rect x="1" y="3" width="18" height="6" rx="1.5" stroke="currentColor"/>
                                  <path d="M20 5v2h2v-2z" fill="currentColor"/>
                                </svg>
                              </div>
                              <div class="phone-nav"></div>
                            </div>
                            <div class="demo-app-screen">
                              <div class="demo-app-header">
                                <h1>Check-ins</h1>
                              </div>
                              <div class="demo-content-area">
                                <div class="demo-action-tiles">
                                  <div class="demo-tile">
                                    <div class="demo-tile-icon"><img src="${icon('briefcase')}" class="lucide-icon" alt=""></div>
                                    <div class="demo-tile-label">My Jobs</div>
                                  </div>
                                  <div class="demo-tile">
                                    <div class="demo-tile-icon"><img src="${icon('users')}" class="lucide-icon" alt=""></div>
                                    <div class="demo-tile-label">Team</div>
                                  </div>
                                  <div class="demo-tile">
                                    <div class="demo-tile-icon"><img src="${icon('archive')}" class="lucide-icon" alt=""></div>
                                    <div class="demo-tile-label">Archived</div>
                                  </div>
                                </div>
                                <div class="demo-empty-state">
                                  <h3>Start capturing proof</h3>
                                  <p>Take a few photos → submit → automatically published everywhere.</p>
                                  <div class="demo-empty-hint"><span>Tap <strong>+</strong> to create a check-in</span></div>
                                </div>
                              </div>
                              <div class="demo-tab-bar">
                                <div class="demo-tab-item demo-tab-active">
                                  <div class="demo-tab-icon"><img src="${icon('clipboard-list')}" class="lucide-icon" alt=""></div>
                                  Your check-ins
                                </div>
                                <div class="demo-fab"><img src="${icon('plus')}" class="lucide-icon" alt=""></div>
                                <div class="demo-tab-item">
                                  <div class="demo-tab-icon"><img src="${icon('user')}" class="lucide-icon" alt=""></div>
                                  Profile
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ============================================================
             FEATURES / BENEFITS SECTION
             ============================================================ -->
        <section class="jcp-section rankings-section" id="features">
          <div class="jcp-container">
            <div class="rankings-header">
              <h2>Benefits that show up in the market</h2>
              <p class="rankings-subtitle">
                JobCapturePro creates proof customers can see, rankings that improve, and demand that compounds.
              </p>
            </div>

            <div class="ranking-factors-grid">
              <div class="ranking-factor-card">
                <div class="factor-icon-wrapper">
                  <img src="${icon('badge-check')}" class="factor-icon" alt="">
                </div>
                <h3 class="factor-title">Verified proof</h3>
                <p class="factor-description">Real check-ins replace claims with proof homeowners trust.</p>
                <div class="factor-stat">
                  <span class="stat-value">Proof</span>
                  <span class="stat-label">from real jobs</span>
                </div>
              </div>

              <div class="ranking-factor-card">
                <div class="factor-icon-wrapper">
                  <img src="${icon('map-pin')}" class="factor-icon" alt="">
                </div>
                <h3 class="factor-title">Local visibility</h3>
                <p class="factor-description">Fresh job activity drives stronger map coverage and ranking.</p>
                <div class="factor-stat">
                  <span class="stat-value">Map</span>
                  <span class="stat-label">coverage grows</span>
                </div>
              </div>

              <div class="ranking-factor-card">
                <div class="factor-icon-wrapper">
                  <img src="${icon('message-square')}" class="factor-icon" alt="">
                </div>
                <h3 class="factor-title">Consistent presence</h3>
                <p class="factor-description">Social and GBP stay active without manual posting.</p>
                <div class="factor-stat">
                  <span class="stat-value">Always</span>
                  <span class="stat-label">on brand</span>
                </div>
              </div>

              <div class="ranking-factor-card">
                <div class="factor-icon-wrapper">
                  <img src="${icon('star')}" class="factor-icon" alt="">
                </div>
                <h3 class="factor-title">More reviews</h3>
                <p class="factor-description">Requests go out while the job is fresh and credible.</p>
                <div class="factor-stat">
                  <span class="stat-value">Reviews</span>
                  <span class="stat-label">on autopilot</span>
                </div>
              </div>

              <div class="ranking-factor-card">
                <div class="factor-icon-wrapper">
                  <img src="${icon('building-2')}" class="factor-icon" alt="">
                </div>
                <h3 class="factor-title">Directory presence</h3>
                <p class="factor-description">Get listed across our verified contractor directory instantly.</p>
                <div class="factor-stat">
                  <span class="stat-value">Directory</span>
                  <span class="stat-label">auto-updated</span>
                </div>
              </div>

              <div class="ranking-factor-card">
                <div class="factor-icon-wrapper">
                  <img src="${icon('phone')}" class="factor-icon" alt="">
                </div>
                <h3 class="factor-title">More calls</h3>
                <p class="factor-description">Visibility multiplies across search, maps, and directory channels.</p>
                <div class="factor-stat">
                  <span class="stat-value">Calls</span>
                  <span class="stat-label">keep coming</span>
                </div>
              </div>
            </div>
            <div class="benefits-cta-row">
              <a href="/demo" class="btn btn-primary">See it in the demo</a>
              <a href="/pricing" class="benefits-cta-link">
                View pricing
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M5 12h14M13 5l7 7-7 7"/>
                </svg>
              </a>
            </div>
          </div>
        </section>

        <!-- ============================================================
             WHO IT'S FOR SECTION
             ============================================================ -->
        <section class="jcp-section rankings-section" id="who-its-for">
          <div class="jcp-container">
            <div class="rankings-header">
              <h2>Built for the way real contracting businesses actually work</h2>
              <p class="rankings-subtitle">
                Designed for real job sites, real crews, and businesses that want completed work to keep driving new demand.
              </p>
            </div>

            <div class="guarantees-grid">
              <a href="#faq" class="guarantee-item" data-faq-target="faq-visibility-proof">
                <div class="guarantee-image-wrapper">
                  <div class="guarantee-image" style="background-image: url('https://jobcapturepro.com/wp-content/uploads/2025/11/crew-768x768.jpg');">
                  </div>
                  <div class="guarantee-badge">For Contractors</div>
                </div>
                <div class="guarantee-content">
                  <strong>Contractors & Trades</strong>
                  <p>Turn every completed job into proof that wins the next one.</p>
                  <div class="guarantee-stat">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Automated</span>
                  </div>
                </div>
              </a>

              <a href="#faq" class="guarantee-item" data-faq-target="faq-integrations-locations">
                <div class="guarantee-image-wrapper">
                  <div class="guarantee-image" style="background-image: url('https://jobcapturepro.com/wp-content/uploads/2025/11/confident-foreman-768x768.jpg');">
                  </div>
                  <div class="guarantee-badge">For Owners</div>
                </div>
                <div class="guarantee-content">
                  <strong>Owners & Office Teams</strong>
                  <p>Automate visibility without chasing photos or posts.</p>
                  <div class="guarantee-stat">
                    <span class="stat-number">0</span>
                    <span class="stat-label">Extra Work</span>
                  </div>
                </div>
              </a>

              <a href="#faq" class="guarantee-item" data-faq-target="faq-training-how">
                <div class="guarantee-image-wrapper">
                  <div class="guarantee-image" style="background-image: url('https://jobcapturepro.com/wp-content/uploads/2025/11/owner-crew-768x768.jpg');">
                  </div>
                  <div class="guarantee-badge">For Crews</div>
                </div>
                <div class="guarantee-content">
                  <strong>Field Crews</strong>
                  <p>Capture once and move on — no extra admin work.</p>
                  <div class="guarantee-stat">
                    <span class="stat-number">1</span>
                    <span class="stat-label">Photo Needed</span>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </section>

        <!-- ============================================================
             DIRECTORY PREVIEW SECTION
             ============================================================ -->
        <section class="jcp-section rankings-section directory-preview" id="directory-preview">
          <div class="jcp-container">
            <div class="rankings-header">
              <h2>Your work powers a public directory homeowners trust</h2>
              <p class="rankings-subtitle">
                As a JobCapturePro member, your business appears in a public directory powered by real activity, verified proof, and trust signals that influence homeowner decisions. Visibility and ranking grow naturally as you use the system.
              </p>
            </div>

            <div class="directory-grid preview-grid">
              <a class="directory-card" href="/directory">
                <span class="directory-badge verified">Verified</span>
                <div class="card-header">
                  <div class="company-mark">
                    <div class="company-avatar">SR</div>
                  </div>
                  <div class="card-header-content">
                    <h3 class="card-name">Summit Roofing</h3>
                  </div>
                </div>
                <div class="card-location">
                  <img src="${icon('map-pin')}" class="lucide-icon lucide-icon-xs" alt="">
                  <span>Austin, TX</span>
                </div>
                <div class="card-meta-row">
                  <span class="meta-inline">
                    <img src="${icon('camera')}" class="lucide-icon lucide-icon-xs" alt="">
                    82 jobs
                  </span>
                  <span class="meta-divider">·</span>
                  <span class="meta-inline">
                    <img src="${icon('clock')}" class="lucide-icon lucide-icon-xs" alt="">
                    Active recently
                  </span>
                </div>
                <div class="card-rating">
                  <div class="stars">★★★★★</div>
                  <span class="rating-text">4.9 (120)</span>
                </div>
                <div class="card-footer">
                  <span class="view-profile">View activity</span>
                </div>
              </a>

              <a class="directory-card directory-card-highlight" href="/directory">
                <span class="directory-badge verified">Verified</span>
                <div class="card-header">
                  <div class="company-mark">
                    <div class="company-avatar">YB</div>
                  </div>
                  <div class="card-header-content">
                    <h3 class="card-name">Your Business</h3>
                  </div>
                </div>
                <div class="card-location">
                  <img src="${icon('map-pin')}" class="lucide-icon lucide-icon-xs" alt="">
                  <span>Your City, ST</span>
                </div>
                <div class="card-meta-row">
                  <span class="meta-inline">
                    <img src="${icon('camera')}" class="lucide-icon lucide-icon-xs" alt="">
                    64 jobs
                  </span>
                  <span class="meta-divider">·</span>
                  <span class="meta-inline">
                    <img src="${icon('clock')}" class="lucide-icon lucide-icon-xs" alt="">
                    Active today
                  </span>
                </div>
                <div class="card-rating">
                  <div class="stars">★★★★★</div>
                  <span class="rating-text">4.8 (98)</span>
                </div>
                <div class="card-footer">
                  <span class="view-profile">View activity</span>
                </div>
              </a>

              <a class="directory-card" href="/directory">
                <span class="directory-badge listed">Listed</span>
                <div class="card-header">
                  <div class="company-mark">
                    <div class="company-avatar">HF</div>
                  </div>
                  <div class="card-header-content">
                    <h3 class="card-name">Heritage Fence Co.</h3>
                  </div>
                </div>
                <div class="card-location">
                  <img src="${icon('map-pin')}" class="lucide-icon lucide-icon-xs" alt="">
                  <span>Houston, TX</span>
                </div>
                <div class="card-meta-row">
                  <span class="meta-inline">
                    <img src="${icon('camera')}" class="lucide-icon lucide-icon-xs" alt="">
                    41 jobs
                  </span>
                  <span class="meta-divider">·</span>
                  <span class="meta-inline">
                    <img src="${icon('clock')}" class="lucide-icon lucide-icon-xs" alt="">
                    Active this week
                  </span>
                </div>
                <div class="card-rating">
                  <div class="stars">★★★★★</div>
                  <span class="rating-text">4.7 (64)</span>
                </div>
                <div class="card-footer">
                  <span class="view-profile">View activity</span>
                </div>
              </a>
            </div>
            <p class="directory-preview-outro">Activity earns attention. Inactive listings fade.</p>
            <div class="directory-preview-cta">
              <a href="/demo" class="btn btn-primary directory-demo-cta">
                <span>See how your listing looks in the demo</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M5 12h14M13 5l7 7-7 7"/>
                </svg>
              </a>
            </div>
          </div>
        </section>

        <!-- ============================================================
             FAQ SECTION
             ============================================================ -->
        <section class="jcp-section rankings-section faq-section" id="faq">
          <div class="jcp-container">
            <div class="rankings-header">
              <h2>FAQ</h2>
              <p class="rankings-subtitle">Clear answers for contractors evaluating the system.</p>
            </div>

            <div class="faq-grid">
              <details class="faq-item" id="faq-setup">
                <summary>What does setup look like?</summary>
                <p>Most companies are live within a few days. We connect your website, set up your locations, and turn on the channels you want so job activity can start publishing immediately.</p>
              </details>

              <details class="faq-item" id="faq-connect">
                <summary>What do I need to connect?</summary>
                <p>JobCapturePro supports HouseCall Pro, CompanyCam, Workiz, and QuickBooks today. If you use a different system and it has an API, we can evaluate a custom integration for higher tier plans. You can also use the mobile app without any integrations.</p>
              </details>

              <details class="faq-item" id="faq-visibility-proof">
                <summary>Will this help with Google Maps visibility?</summary>
                <p>Yes. Consistent job activity supports stronger local relevance signals and helps keep your Google Business Profile fresh. Many contractors see improved visibility when posting becomes consistent and automatic.</p>
              </details>

              <details class="faq-item">
                <summary>Is this real activity or staged content?</summary>
                <p>It is real. Proof comes from your actual jobs and your actual photos. The system is designed to show authentic work activity, not generic marketing content.</p>
              </details>

              <details class="faq-item" id="faq-training-how">
                <summary>Do crews need to learn new tools?</summary>
                <p>No. Crews simply take a photo in the JobCapturePro app, or your jobs flow in automatically through integrations. There is no extra admin work and no new process to teach.</p>
              </details>

              <details class="faq-item">
                <summary>What exactly happens after we capture a job?</summary>
                <p>One photo or a completed job triggers an AI check in that becomes publishable proof. It can update your website, keep your Google Business Profile active, publish social content, and send a review request based on your settings.</p>
              </details>

              <details class="faq-item">
                <summary>Where does proof get published?</summary>
                <p>Your proof can appear on your website, your Google Business Profile, your social channels, inside your JobCapturePro public directory listing, and in review requests you send to customers.</p>
              </details>

              <details class="faq-item">
                <summary>How do reviews work?</summary>
                <p>You can request reviews right after jobs while the experience is fresh. Reviews can be triggered from the mobile app or automatically after job completion through CRM based automation. Follow ups can be enabled and stop once the customer clicks to leave a review.</p>
              </details>

              <details class="faq-item" id="faq-integrations-locations">
                <summary>Can we use JobCapturePro for multiple locations?</summary>
                <p>Yes. Each location can have its own Google Business Profile and connected social accounts, with organization level management for multi location teams.</p>
              </details>

              <details class="faq-item">
                <summary>What is the JobCapturePro public directory and why does it matter?</summary>
                <p>Every member gets a public directory listing that is powered by real job activity and proof. Homeowners can see who is active and credible, and companies that post consistently earn stronger placement and more trust over time.</p>
              </details>
            </div>
          </div>
        </section>

        <!-- ============================================================
             CONVERSION SECTION
             ============================================================ -->
        <section class="jcp-section rankings-section conversion-section" id="conversion">
          <div class="jcp-container">
            <div class="conversion-wrapper">
              <div class="conversion-content">
                <div class="rankings-header">
                  <h2>This works when the work is real</h2>
                  <p class="rankings-subtitle">
                    JobCapturePro is built for contractors who already complete real jobs and move on to the next one. It turns finished work into quiet, ongoing visibility so the jobs you complete today continue to bring in calls later.
                  </p>
                </div>
                <div class="conversion-points">
                  <div class="conversion-point">
                    <div class="conversion-point-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                      </svg>
                    </div>
                    <div class="conversion-point-text">
                      <strong>You're already completing jobs on a regular basis</strong>
                    </div>
                  </div>
                  <div class="conversion-point">
                    <div class="conversion-point-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                      </svg>
                    </div>
                    <div class="conversion-point-text">
                      <strong>You want more calls without adding admin or marketing tasks</strong>
                    </div>
                  </div>
                  <div class="conversion-point">
                    <div class="conversion-point-icon">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                      </svg>
                    </div>
                    <div class="conversion-point-text">
                      <strong>You want past work to keep generating future work</strong>
                    </div>
                  </div>
                </div>
                <div class="conversion-cta">
                  <a href="/demo" class="btn btn-primary conversion-cta-btn">
                    See how this works for your business
                  </a>
                </div>
              </div>
              <div class="conversion-visual">
                <div class="conversion-image-wrapper">
                  <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&h=600&fit=crop&q=80" alt="Contractors at work" class="conversion-image">
                  <div class="conversion-image-overlay">
                    <div class="conversion-badge">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                      </svg>
                      <span>Real Work</span>
                    </div>
                    <div class="conversion-stats">
                      <div class="conversion-stat-item">
                        <div class="conversion-stat-number">100%</div>
                        <div class="conversion-stat-label">Automated</div>
                      </div>
                      <div class="conversion-stat-item">
                        <div class="conversion-stat-number">0</div>
                        <div class="conversion-stat-label">Extra Work</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ============================================================
             FINAL CTA SECTION
             ============================================================ -->
        <section class="jcp-section rankings-section">
          <div class="jcp-container">
            <div class="rankings-cta">
              <div class="cta-content">
                <h3>See how your jobs turn into real demand</h3>
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

    // Rotating word in hero title: visibility → calls → customers → growth
    const rotatingWordEl = root.querySelector('.jcp-hero-rotating-word');
    if (rotatingWordEl) {
      const words = ['visibility', 'calls', 'customers', 'growth'];
      let index = 0;
      const cycleMs = 2800;
      const fadeMs = 350;
      setInterval(() => {
        rotatingWordEl.style.opacity = '0';
        setTimeout(() => {
          index = (index + 1) % words.length;
          rotatingWordEl.textContent = words[index];
          rotatingWordEl.style.opacity = '1';
        }, fadeMs);
      }, cycleMs);
    }

    // Add interactive animations to hero card
    setTimeout(() => {
      const heroCard = document.querySelector('.hero-media-card');
      if (!heroCard) return;

      // Parallax effect on mouse move
      heroCard.addEventListener('mousemove', (e) => {
        const rect = heroCard.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const rotateX = (y - centerY) / 20;
        const rotateY = (centerX - x) / 20;

        heroCard.style.transform = `translateY(-16px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
      });

      heroCard.addEventListener('mouseleave', () => {
        heroCard.style.transform = '';
      });

      // Animate stats on scroll into view
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const stats = entry.target.querySelectorAll('.hero-stat');
            stats.forEach((stat, index) => {
              setTimeout(() => {
                stat.style.animation = `statsSlideUp 0.6s ease-out both`;
                stat.style.opacity = '1';
              }, index * 100);
            });
          }
        });
      }, { threshold: 0.3 });

      if (heroCard) {
        observer.observe(heroCard);
      }
    }, 100);

    // Background animation removed - using CSS background image instead

    // Clickable guarantee cards - scroll to FAQ and expand relevant item
    setTimeout(() => {
      const guaranteeItems = document.querySelectorAll('.guarantee-item[data-faq-target]');
      guaranteeItems.forEach((item) => {
        item.addEventListener('click', (e) => {
          e.preventDefault();
          const targetId = item.getAttribute('data-faq-target');
          const faqSection = document.getElementById('faq');
          const targetFaq = document.getElementById(targetId);
          
          if (faqSection) {
            // Scroll to FAQ section
            faqSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            
            // Expand the target FAQ item after a short delay
            setTimeout(() => {
              if (targetFaq && targetFaq.tagName === 'DETAILS') {
                targetFaq.open = true;
                // Scroll the FAQ item into view
                targetFaq.scrollIntoView({ behavior: 'smooth', block: 'center' });
              }
            }, 300);
          }
        });
      });
    }, 300);
  };
})();
