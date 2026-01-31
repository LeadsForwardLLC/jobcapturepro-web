/* =========================================================
   JobCapturePro Demo JS (Clean, Organized, Stable)
   - Keeps all features currently used
   - Preserves global functions called by HTML onclick
   - Fixes broken handlers / stray code / flow issues
========================================================= */

/* =========================
   Personalization (Survey)
========================= */
let demoUser = {
  firstName: 'John',
  lastName: 'Smith',
  businessName: 'Summit Plumbing',
  niche: 'service'
};

const baseUrl = window.JCP_CONFIG && window.JCP_CONFIG.baseUrl
  ? window.JCP_CONFIG.baseUrl
  : window.location.origin;
const assetBase = window.JCP_ASSET_BASE || '';

// Optional URL params for email links: ?mode=run&name=Jane&business=ABC+Plumbing&niche=plumbing
function getDemoUserFromUrl() {
  try {
    const params = new URLSearchParams(window.location.search);
    const name = params.get('name') || params.get('first_name');
    const business = params.get('business') || params.get('company');
    const niche = params.get('niche') || params.get('business_type');
    const email = params.get('email');
    if (name || business || niche || email) {
      return {
        firstName: decodeURIComponent(name || '').trim() || demoUser.firstName,
        businessName: decodeURIComponent(business || '').trim() || demoUser.businessName,
        niche: decodeURIComponent(niche || '').trim() || demoUser.niche,
        email: decodeURIComponent(email || '').trim() || ''
      };
    }
  } catch (e) {
    // ignore
  }
  return null;
}

try {
  const fromUrl = getDemoUserFromUrl();
  if (fromUrl) {
    demoUser = {
      firstName: fromUrl.firstName || demoUser.firstName,
      lastName: fromUrl.lastName || demoUser.lastName,
      businessName: fromUrl.businessName || demoUser.businessName,
      niche: fromUrl.niche || demoUser.niche,
      email: fromUrl.email || demoUser.email || ''
    };
    try {
      localStorage.setItem('demoUser', JSON.stringify(demoUser));
    } catch (e) {
      // ignore
    }
  } else {
    const stored = localStorage.getItem('demoUser');
    if (stored) {
      const parsed = JSON.parse(stored);

      demoUser = {
        firstName: parsed.firstName || demoUser.firstName,
        lastName: parsed.lastName || demoUser.lastName,
        businessName: parsed.businessName || demoUser.businessName,
        niche: parsed.niche || demoUser.niche,
        email: parsed.email || ''
      };
    }
  }
} catch (e) {
  console.warn('Demo personalization fallback used');
}


/* ---------------------------
   Demo Assets
---------------------------- */
const demoPhotos = [
  'https://images.unsplash.com/photo-1585704032915-c3400ca199e7?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1581858726788-75bc0f6a952d?w=400&h=400&fit=crop',
  'https://images.unsplash.com/photo-1607400201889-565b1ee75f8e?w=400&h=400&fit=crop'
];

const descriptions = [
  'Replaced an aging water heater and brought the system up to code. We installed a new high-efficiency unit, verified proper venting, and tested temperature + pressure relief for safe operation. Customer is back to consistent hot water with improved energy performance.',
  'Completed a full water heater swap-out: removed the failing tank, installed a new unit, reconnected lines, and confirmed there are no leaks. Verified ignition and heating cycle, set the thermostat, and cleaned up the work area before departure.',
  'Installed a new water heater and ensured everything is running safely and efficiently. Connections were tightened, valves were tested, and we confirmed stable hot-water delivery throughout the home.'
];

/* ---------------------------
   State
---------------------------- */
const state = {
  currentScreen: 'login-screen',
  hasPublished: false,
  guideDisabled: false,
  isFinalStep: false, // ← ADD THIS
  photoCount: 0,
  metrics: { checkins: 12, posts: 36, reviews: 48 },
  currentDescriptionIndex: 0,
  savedCheckins: [],
  map: null,
  mapMarkers: [],
  activeCheckinIndex: null,
  guideHidden: false,
};

/* ---------------------------
   Guide Content
---------------------------- */
const demoGuideContent = {
  step1: {
    pill: 'Step 1',
    title: 'Start the demo',
    body: 'Click “Start Demo” to run the walkthrough.'
  },
  step2: {
    pill: 'Step 2',
    title: 'Tap + to create a check-in',
    body: 'This is what your tech does on each job. One quick check-in powers everything.'
  },
  step3: {
    pill: 'Step 3',
    title: 'Add a photo, then submit',
    body: 'Photos alone generate the job content. Add one photo and press “Submit”.'
  },
  step4: {
    pill: 'Step 4',
    title: 'Publish the job',
    body: 'Tap “Save & Publish” to push this job to your website + Google + Facebook.'
  },
  step5: {
    pill: 'Step 5',
    title: 'Request a review',
    body: 'Now send a review request.'
  },
  step6: {
    pill: 'Final Step',
    title: 'View your listing in the directory',
    body: 'Your completed job unlocks your verified directory listing. Click to view how customers see you.'
  }
};


/* ---------------------------
   DOM Helpers
---------------------------- */
const $ = (id) => document.getElementById(id);

function safeText(id, value) {
  const el = $(id);
  if (el) el.textContent = value;
}

function applyPersonalization() {
  // Website header title
  const h1 = document.querySelector('.website-header h1');
  if (h1) h1.textContent = `Recent Work from ${demoUser.businessName}`;

  // Optional: personalize browser URL pill (if present)
  const urlPill = document.querySelector('.browser-url');
  if (urlPill) {
    const slug = demoUser.businessName.toLowerCase().replace(/[^a-z0-9]+/g, '');
    urlPill.textContent = `${slug}.com/jobs`;
  }
}


/* ---------------------------
   Utilities
---------------------------- */
function wait(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

function pulse(id) {
  const el = $(id);
  if (!el) return;
  el.classList.add('pulsing');
  setTimeout(() => el.classList.remove('pulsing'), 900);
}

async function pulseSequence(ids, delay = 350) {
  for (const id of ids) {
    pulse(id);
    await wait(delay);
  }
}

/* =========================================================
   Layout Systems
   - Tour docking
   - Focal points
   - Mobile mode
========================================================= */

function applyFocalPoint() {
  const zones = {
    phone: $('focus-phone'),
    website: $('focus-website'),
    social: $('focus-social')
  };

// PERMANENTLY DISABLE GUIDED DIMMING
if (state.guideDisabled || state.isFinalStep) {
  document.body.classList.remove('show-website', 'show-social');

  document
    .querySelectorAll('.is-dimmed, .is-focused')
    .forEach(el => {
      el.classList.remove('is-dimmed');
      el.classList.remove('is-focused');
    });

  return;
}


  // Default behavior
  Object.values(zones).forEach(el => {
    if (!el) return;
    el.classList.add('is-dimmed');
    el.classList.remove('is-focused');
  });

  const focus = (el) => {
    if (!el) return;
    el.classList.remove('is-dimmed');
    el.classList.add('is-focused');
  };

  switch (tour.stepKey) {
    case 'step1':
    case 'step2':
    case 'step3':
    case 'step4':
      focus(zones.phone);
      break;

    case 'step5':
      focus(zones.social);
      break;

    default:
      focus(zones.website);
  }
    // Mobile visibility control (responsive guided mode)
    document.body.classList.toggle(
      'show-website',
      ['step4', 'step6'].includes(tour.stepKey)
    );

    document.body.classList.toggle(
      'show-social',
      tour.stepKey === 'step5'
    );
}

function applyMobileMode() {
  const isMobile = window.matchMedia('(max-width: 820px)').matches;
  document.body.classList.toggle('is-mobile-mode', isMobile);

  const stepper = $('mobile-stepper');
  if (stepper) stepper.style.display = isMobile ? 'flex' : 'none';
}

/* =========================================================
   Floating Tour Tooltip (moves to target, collapsible)
========================================================= */

const tour = {
  isHidden: false,
  isMinimized: false,

  // This is the ONLY thing that should control what the tooltip says
  stepKey: 'step1',

    anchors: {
      step1: '#btnStartDemo',
      step2: '#fabNewCheckin',
      step3: '#submit-btn',
      step4: '#btnSavePublish',
      step5: '#btnRequestReview',
      step6: '#btnViewDirectory',
    }
};


function showTour() {
  const el = $('tour-float');
  const bubble = $('tour-bubble');
  if (!el || !bubble) return;

  bubble.classList.add('is-expanding');

  el.classList.remove('is-hidden');
  bubble.classList.add('is-hidden');

  setTimeout(() => {
    bubble.classList.remove('is-expanding');
  }, 200);

  tour.isHidden = false;
  tour.isMinimized = false;
}

function minimizeTour() {
  if (state.isFinalStep) return;

  const el = $('tour-float');
  const bubble = $('tour-bubble');
  if (!el || !bubble) return;

  el.classList.add('is-minimizing');

  setTimeout(() => {
    el.classList.add('is-hidden');
    el.classList.remove('is-minimizing');
    bubble.classList.remove('is-hidden');
  }, 300);

  tour.isMinimized = true;
}

function closeTour() {
  const el = $('tour-float');
  const bubble = $('tour-bubble');

  if (el) el.classList.add('is-hidden');
  if (bubble) bubble.classList.add('is-hidden');

  tour.isHidden = true;
  tour.isMinimized = false;
}

function setTourStep(stepKey) {
  const tourEl = document.getElementById('tour-float');
  state.isFinalStep = stepKey === 'step6';
  const bubble = document.getElementById('tour-bubble');

  if (state.isFinalStep && bubble) {
    bubble.classList.add('is-hidden');
  }
    if (tourEl) {
      tourEl.classList.toggle('final-step', state.isFinalStep);
    }
  tour.stepKey = stepKey;
  updateTourNextLabel(getNextLabelForStep(stepKey));
  // Disable back buttons during guided steps (prevents breaking the flow)
  lockBackButtons(['step2','step3','step4','step5'].includes(stepKey));

    //Control Request Review button by tour step
    const requestReviewBtn = document.getElementById('btnRequestReview');

    if (requestReviewBtn) {
      const enabled = stepKey === 'step5';
      requestReviewBtn.disabled = !enabled;
      requestReviewBtn.classList.toggle('is-disabled', !enabled);
    }

    const minimizeBtn = document.getElementById('tour-minimize');
    const nextBtn = document.getElementById('tour-next');

    if (state.isFinalStep) {
      minimizeBtn?.classList.add('is-hidden');
      nextBtn?.classList.add('is-hidden');

      // Final step = close-only tooltip
      document.getElementById('tour-close')?.classList.remove('is-hidden');
    } else {
      minimizeBtn?.classList.remove('is-hidden');
      nextBtn?.classList.remove('is-hidden');
      document.getElementById('tour-close')?.classList.add('is-hidden');
    }

    const fab = document.getElementById('fabNewCheckin');
    const checkins = document.querySelectorAll('.home-checkin-item');

    // Re-enable interactive elements for guided steps
    if (fab) {
      fab.classList.remove('is-disabled');
    }

    checkins.forEach(item => {
      item.classList.remove('is-disabled');
    });

  updateTourFloating();
}

function getNextLabelForStep(stepKey) {
  if (stepKey === 'step4') return 'Publish →';
  if (stepKey === 'step5') return 'Send Review →';
  if (stepKey === 'step6') return 'View Directory →';
  return 'Next →';
}

function updateTourFloating() {
  if (tour.isHidden || tour.isMinimized) return;

  const step = demoGuideContent[tour.stepKey];
  if (!step) return;

  if (state.isFinalStep) {
    safeText('tour-pill', '');
    safeText('tour-float-title', 'View your live directory');
    safeText('tour-float-body', 'This is now visible to customers.');
  } else {
    safeText('tour-pill', step.pill);
    safeText('tour-float-title', step.title);
    safeText('tour-float-body', step.body);
  }

  positionTourNear();
  
  // Auto-fade tooltip after 5 seconds for final step
  if (state.isFinalStep) {
    const tourFloat = document.getElementById('tour-float');
    if (tourFloat && !tourFloat.dataset.fadeScheduled) {
      tourFloat.dataset.fadeScheduled = 'true';
      setTimeout(() => {
        if (tourFloat && state.isFinalStep) {
          tourFloat.style.transition = 'opacity 0.5s ease-out';
          tourFloat.style.opacity = '0';
          setTimeout(() => {
            if (tourFloat) {
              tourFloat.classList.add('is-hidden');
              tourFloat.style.opacity = '';
              tourFloat.style.transition = '';
              delete tourFloat.dataset.fadeScheduled;
            }
          }, 500);
        }
      }, 5000);
    }
  }
}

function positionTourNear() {
  const floatEl = $('tour-float');
  const arrow = $('tour-arrow');
  if (!floatEl || !arrow) return;

  const selector = tour.anchors[tour.stepKey];
  const isReviewStep = tour.stepKey === 'step5';
  const isFinalStep = tour.stepKey === 'step6';
  const target = document.querySelector(selector);
  if (!target) return;

  floatEl.classList.remove('is-hidden');

  const r = target.getBoundingClientRect();
  const w = floatEl.offsetWidth;
  const h = floatEl.offsetHeight;
  const pad = 12;

  let top, left, side;

  // STEP 6: center below "View Demo Directory" button
  if (isFinalStep) {
    // BELOW the header button, arrow points UP
    top = r.bottom + 12;
    left = r.left + r.width / 2 - w / 2;
    // Ensure it doesn't go off-screen to the left
    if (left < pad) {
      left = pad;
    }
    // Ensure it doesn't go off-screen to the right
    if (left + w > window.innerWidth - pad) {
      left = window.innerWidth - w - pad;
    }
    side = 'top';
  } else if (isReviewStep) {
    top = r.bottom + 14;
    left = r.left + r.width / 2 - w / 2;
    side = 'top';
  } else {
    top = r.top + r.height / 2 - h / 2;
    left = r.right + 14;
    side = 'right';
  }

  if (left + w > window.innerWidth) {
    left = r.left - w - 14;
    side = 'left';
  }

  top = Math.max(pad, Math.min(top, window.innerHeight - h - pad));
  left = Math.max(pad, Math.min(left, window.innerWidth - w - pad));

  floatEl.style.top = `${Math.round(top)}px`;
  floatEl.style.left = `${Math.round(left)}px`;

  if (side === 'bottom') {
    arrow.style.bottom = `-7px`;
    arrow.style.top = 'auto';
    arrow.style.left = `${Math.round(w / 2 - 7)}px`;
    arrow.style.right = 'auto';
  } else if (side === 'top') {
    arrow.style.top = `-7px`;
    arrow.style.bottom = 'auto';
    arrow.style.left = `${Math.round(w / 2 - 7)}px`;
    arrow.style.right = 'auto';
  } else {
    arrow.style.top = `${Math.round(r.top + r.height / 2 - top)}px`;
    arrow.style.left = side === 'right' ? `-7px` : 'auto';
    arrow.style.right = side === 'left' ? `-7px` : 'auto';
    arrow.style.bottom = 'auto';
  }
}

function syncAttentionAnimations() {
  const startBtn = $('btnStartDemo');
  const fab = $('fabNewCheckin');
  const submitBtn = $('submit-btn');

  // Clear all
  startBtn?.classList.remove('wiggle-attention');
  fab?.classList.remove('fab-glow', 'fab-attention');
  submitBtn?.classList.remove('wiggle-attention');

  if (state.currentScreen === 'login-screen') {
    startBtn?.classList.add('wiggle-attention');
  }

  if (state.currentScreen === 'home-screen' && state.savedCheckins.length === 0) {
    fab?.classList.add('fab-attention');
  }

  if (state.currentScreen === 'new-screen') {
    // Only if they have photos
    if (state.photoCount >= 1) submitBtn?.classList.add('wiggle-attention');
  }
}

/* =========================================================
   Screens / Navigation
========================================================= */
function lockBackButtons(lock) {
  const buttons = ['btnBackToHome', 'btnEditBack'];
  buttons.forEach(id => {
    const btn = document.getElementById(id);
    if (!btn) return;
    btn.classList.toggle('is-disabled', lock);
    btn.disabled = lock;
  });
}

function setScreen(screenId) {
  document.querySelectorAll('.app-screen').forEach(s => s.classList.remove('active'));
  const target = $(screenId);
  if (target) target.classList.add('active');

  state.currentScreen = screenId;

// Tour is step-driven, not screen-driven
updateTourFloating();


if (['login-screen','home-screen','new-screen','edit-screen'].includes(screenId)) {
  applyFocalPoint();
}

if (screenId === 'edit-screen' && !state.hasPublished) {
  updateTourNextLabel('Publish →');
} else if (screenId === 'edit-screen' && state.hasPublished) {
  updateTourNextLabel('Request Review →');
} else {
  updateTourNextLabel('Next →');
}

  // Attention
  syncAttentionAnimations();
}

/* =========================================================
   Website Preview (Right Panel)
========================================================= */

function initializeWebsite() {
  const container = $('website-checkins');
  if (!container) return;

  container.innerHTML = `
    <div class="empty-state" id="website-empty">
      <h3>Your published check-ins appear here</h3>
      <p>
        Publish a check-in to automatically add a job card here (photos, location, proof of work).
      </p>
    </div>
  `;

  applyPersonalization();
}

const header = document.querySelector('.website-header h1');
if (header) {
  header.textContent = `Recent Work from ${demoUser.businessName}`;
}

function createCheckinCard(checkin, isNew = false) {
  return `
    <div class="checkin-card ${isNew ? 'new' : ''}">
      <div class="checkin-image">
        <img src="${checkin.image}" alt="${checkin.title}">
      </div>
      <div class="checkin-content">
        <div class="checkin-title">${checkin.title}</div>
        <div class="checkin-meta">
          <img src="${assetBase}/shared/assets/icons/lucide/map-pin.svg" class="lucide-icon lucide-icon-sm" alt="">
          <span>Near ${checkin.location}</span>
        </div>
        <div class="checkin-meta">
          <img src="${assetBase}/shared/assets/icons/lucide/calendar.svg" class="lucide-icon lucide-icon-sm" alt="">
          <span>${checkin.date}</span>
        </div>
      </div>
    </div>
  `;
}

function loadSampleCheckins() {
  if (state.savedCheckins.length > 0) return;

  const samples = Array.from({ length: 6 }).map((_, i) => ({
    title: 'Service Job Completed',
    address: `${100 + i} Main St`,
    location: 'Austin, TX',
    summary: 'Completed professional service work.',
    customer: 'Customer',
    time: `${i + 1}d ago`,
    image: demoPhotos[i % demoPhotos.length]
  }));

  samples.forEach(job => state.savedCheckins.push(job));

  // Phone
  renderHomeCheckins();

  // Website
  const websiteContainer = document.getElementById('website-checkins');
  document.getElementById('website-empty')?.remove();

  samples.forEach(job => {
    websiteContainer.insertAdjacentHTML(
      'beforeend',
      createCheckinCard({
        title: job.title,
        location: job.location,
        date: 'Recent',
        image: job.image
      })
    );
  });

  // Metrics
  state.metrics.checkins += samples.length;
  state.metrics.posts += samples.length * 3;

  safeText('metric-checkins', state.metrics.checkins);
  safeText('metric-posts', state.metrics.posts);
}



/* =========================================================
   Home Screen (Phone)
========================================================= */

function renderHomeCheckins() {
  const list = $('home-checkin-list');
  const emptyState = document.querySelector('#home-screen .empty-state');
  if (!list) return;

  list.innerHTML = '';
  
  // Load and display pending jobs
  let pendingJobs = [];
  try {
    const stored = localStorage.getItem('jcpPendingJobs');
    if (stored) {
      pendingJobs = JSON.parse(stored);
    }
  } catch (e) {
    console.warn('Could not load pending jobs');
  }
  
  // Show pending jobs first
  pendingJobs.forEach((job, index) => {
    const item = document.createElement('div');
    item.className = 'home-checkin-item pending-job-item';

    item.innerHTML = `
      <div class="home-checkin-left">
        <div class="pending-pill">Next job ready</div>
        <h3>${job.address}</h3>
        <div class="home-checkin-location">${job.city}</div>
        <div class="home-checkin-desc">
          ${job.scopeSummary.service} · ${job.scopeSummary.scope}
        </div>
        <div class="home-checkin-meta">
          <span class="home-checkin-user">${job.status}</span>
          <span class="home-checkin-time">Tap to start</span>
        </div>
      </div>

      <div class="home-checkin-thumb">
        <div class="pending-icon">
          <i class="fas fa-clipboard-check"></i>
        </div>
      </div>
    `;
    item.onclick = () => startPendingJob(job, index);
    list.appendChild(item);
  });

  if (state.savedCheckins.length === 0 && pendingJobs.length === 0) {
    if (emptyState) emptyState.style.display = 'block';
    return;
  }

  if (emptyState) emptyState.style.display = 'none';

  state.savedCheckins.forEach((checkin, index) => {
    const item = document.createElement('div');
    item.className = 'home-checkin-item';

    item.innerHTML = `
      <div class="home-checkin-left">
        <h3>${checkin.address || '105 Walnut St'}</h3>
        <div class="home-checkin-location">${checkin.location}</div>
        <div class="home-checkin-desc">
          ${checkin.summary || 'Replaced water heater.'}
        </div>
        <div class="home-checkin-meta">
          <span class="home-checkin-user">${checkin.customer || 'John Doe'}</span>
          <span class="home-checkin-time">${checkin.time || '2h ago'}</span>
        </div>
      </div>

      <div class="home-checkin-thumb">
        <img src="${checkin.image}" alt="Job photo">
      </div>
    `;
    item.onclick = () => openCheckinForEdit(index);
    list.appendChild(item);
  });
}

function startPendingJob(job, jobIndex) {
  // Preload the job context and go directly to edit screen
  setScreen('edit-screen');
  
  // Populate edit screen with pending job data
  const addressEl = document.querySelector('#edit-screen .location-info h3');
  const cityEl = document.querySelector('#edit-screen .location-info p');
  const descEl = $('description-field');
  
  if (addressEl) addressEl.textContent = job.address;
  if (cityEl) cityEl.textContent = job.city;
  if (descEl) {
    descEl.value = `${job.scopeSummary.service} ${job.scopeSummary.scope}. ${job.scopeSummary.notes || 'Prepared from estimate.'}`;
  }
  
  // Remove from pending jobs after starting
  try {
    let pending = JSON.parse(localStorage.getItem('jcpPendingJobs') || '[]');
    pending.splice(jobIndex, 1);
    localStorage.setItem('jcpPendingJobs', JSON.stringify(pending));
  } catch (e) {
    console.warn('Could not update pending jobs');
  }
}

/* =========================================================
   Map (Leaflet)
========================================================= */

function initializeMap() {
  if (state.map) return;

  // Safety: only init if the element exists
  const mapEl = $('job-map');
  if (!mapEl || typeof L === 'undefined') return;

  state.map = L.map('job-map').setView([30.2672, -97.7431], 11);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(state.map);
}

function addMapMarker(lat, lng, title) {
  if (!state.map || typeof L === 'undefined') return;

  const marker = L.marker([lat, lng])
    .addTo(state.map)
    .bindPopup(`<strong>${title}</strong>`);

  state.mapMarkers.push(marker);

  const group = new L.featureGroup(state.mapMarkers);
  state.map.fitBounds(group.getBounds().pad(0.3));
}

/* =========================================================
   New Check-in Flow (Phone)
========================================================= */
function goToHome() {
  setScreen('home-screen');
  renderHomeCheckins();

  applyFocalPoint();
  updateTourFloating();
}

function goToNew() {
  setScreen('new-screen');

  state.photoCount = 0;
  const grid = $('photo-grid');
  if (grid) grid.innerHTML = '';

  const submit = $('submit-btn');
  if (submit) {
    submit.disabled = true;
    submit.onclick = null;
  }
  if (tour.stepKey === 'step2') {
    setTourStep('step3');
    applyFocalPoint();
    updateTourFloating();
  }
  syncAttentionAnimations();
}

function addPhotos() {
  if (state.photoCount >= 3) return;

  const grid = $('photo-grid');
  if (!grid) return;

  const idx = state.photoCount;

  const photoDiv = document.createElement('div');
  photoDiv.className = 'photo-item';
  photoDiv.innerHTML = `
    <img src="${demoPhotos[idx]}" alt="Job photo">
    <button class="photo-remove" type="button">×</button>
  `;

  // Remove handler (safe)
  photoDiv.querySelector('.photo-remove')?.addEventListener('click', () => {
    photoDiv.remove();
    state.photoCount = Math.max(0, state.photoCount - 1);
    updateSubmitButtonState();
    syncAttentionAnimations();
  });

  grid.appendChild(photoDiv);
  state.photoCount++;

  updateSubmitButtonState();
  syncAttentionAnimations();
}

function updateSubmitButtonState() {
  const btn = $('submit-btn');
  if (!btn) return;

  if (state.photoCount >= 1) {
    btn.disabled = false;
    btn.classList.remove('is-disabled');
    btn.onclick = processPhotos;
  } else {
    btn.disabled = true;
    btn.classList.add('is-disabled');
    btn.onclick = null;
  }
}

async function processPhotos() {
  const overlay = $('processing');
  if (!overlay) {
    // Fallback
    showEditScreen();
    return;
  }

  overlay.classList.add('active');

  await wait(700);
  markProcessingStepDone('step1');

  await wait(700);
  markProcessingStepDone('step2');

  await wait(700);
  markProcessingStepDone('step3');

  await wait(350);
  overlay.classList.remove('active');

  // Reset steps
  setTimeout(() => resetProcessingSteps(['step1','step2','step3']), 400);

  showEditScreen();
}

function markProcessingStepDone(stepId) {
  const step = $(stepId);
  if (!step) return;
  step.classList.add('done');
  const icon = step.querySelector('.step-icon');
  if (icon) icon.innerHTML = `<img src="${assetBase}/shared/assets/icons/lucide/check.svg" class="lucide-icon lucide-icon-sm" alt="">`;
}

function resetProcessingSteps(ids) {
  ids.forEach(id => {
    const step = $(id);
    if (!step) return;
    step.classList.remove('done');
    const icon = step.querySelector('.step-icon');
    if (icon) icon.textContent = '';
  });
}

function showEditScreen() {
  const editGrid = $('edit-photo-grid');
  if (editGrid) {
    editGrid.innerHTML = '';
    for (let i = 0; i < state.photoCount; i++) {
      const photoDiv = document.createElement('div');
      photoDiv.className = 'photo-item';
      photoDiv.innerHTML = `<img src="${demoPhotos[i]}" alt="Job photo">`;
      editGrid.appendChild(photoDiv);
    }
  }

  setScreen('edit-screen');
  setTourStep('step4');

  // AUTO-SCROLL TO PUBLISH BUTTON (REAL SCROLLER)
  setTimeout(() => {
    const scroller = document.querySelector('#edit-screen .content-area');
    const publishBtn = document.getElementById('btnSavePublish');

    if (!scroller || !publishBtn) return;

    scroller.scrollTo({
      top: publishBtn.offsetTop - 60,
      behavior: 'smooth'
    });

    // RE-ANCHOR TOUR AFTER SCROLL
    setTimeout(() => {
      updateTourFloating();
    }, 300);
  }, 500);
}


function regenerateDescription() {
  state.currentDescriptionIndex = (state.currentDescriptionIndex + 1) % descriptions.length;

  const field = $('description-field');
  if (!field) return;

  field.value = descriptions[state.currentDescriptionIndex];

  field.classList.add('is-fading');
  setTimeout(() => {
    field.classList.remove('is-fading');
  }, 180);
}

/* =========================================================
   Save / Publish / Social / Review
========================================================= */

async function saveCheckin() {
const publishBtn = $('btnSavePublish');
if (publishBtn) {
  publishBtn.disabled = true;
  publishBtn.textContent = 'Published';
  publishBtn.classList.add('is-disabled');
}
  const descField = $('description-field');

  // Edit existing
  if (state.activeCheckinIndex !== null) {
    const existing = state.savedCheckins[state.activeCheckinIndex];
    if (existing) {
      existing.summary = descField ? descField.value : existing.summary;
      existing.time = 'Just now';
    }
    state.activeCheckinIndex = null;
  } else {
    // Create new
    state.savedCheckins.push({
      title: 'Water Heater Replacement',
      address: '105 Walnut St',
      location: 'Austin, TX',
      summary: 'Replaced water heater.',
      customer: 'John Doe',
      time: 'Just now',
      image: demoPhotos[0]
    });

    initializeMap();
    addMapMarker(30.2672, -97.7431, 'Water Heater Replacement');
  }

  // Metrics
  state.metrics.checkins++;
  state.metrics.posts += 3;

  safeText('metric-checkins', String(state.metrics.checkins));
  safeText('metric-posts', String(state.metrics.posts));

    // Publish to WEBSITE first (this is the trigger moment)
    const websiteContainer = document.getElementById('website-checkins');
    if (!websiteContainer) return;

    // 1) Replace the LEFT placeholder with the checkin card
const empty = document.getElementById('website-empty');
if (empty) empty.remove();

websiteContainer.insertAdjacentHTML(
  'afterbegin',
  createCheckinCard({
    title: 'Water Heater Replacement',
    location: 'Austin, TX',
    date: new Date().toLocaleDateString('en-US', {
      month: 'long',
      day: 'numeric',
      year: 'numeric'
    }),
    image: demoPhotos[0]
  }, true)
);

    // MARK AS PUBLISHED
    state.hasPublished = true;

    // EXIT GUIDED MODE PERMANENTLY
    state.guideDisabled = true;

    // Publish to social
    await publishToSocial();

    // Pulse through channels
    await pulseSequence(['website-checkins', 'sim-google', 'sim-facebook'], 350);

    // FULL VISIBILITY AFTER PUBLISH
    applyFocalPoint();

    // Advance tour to review step
    state.currentScreen = 'edit-screen';
    setTourStep('step5');

    // CRITICAL: force tooltip + layout refresh
    updateTourFloating();
}

async function publishToSocial() {
  // Remove empty states
  $('google-empty')?.remove();
  $('facebook-empty')?.remove();

  const jobCard = `
    <div class="feed-card">
      <div class="feed-image"><img src="${demoPhotos[0]}" alt="Job"></div>
      <div class="feed-content">
        <h4>Water Heater Replacement • Austin, TX</h4>
        <p>Posted today • Professional installation</p>
      </div>
    </div>
  `;

  pulse('sim-google');
  $('feed-google')?.insertAdjacentHTML('afterbegin', jobCard);

  await wait(350);

  pulse('sim-facebook');
  $('feed-facebook')?.insertAdjacentHTML('afterbegin', jobCard);
}

function openReviewDialog() {
  $('review-modal')?.classList.add('active');
}

function closeReviewDialog() {
  $('review-modal')?.classList.remove('active');
}

function buildDirectoryPreview() {
  const businessName = demoUser.businessName || 'Your Business';
  const nicheLabel = demoUser.niche
    ? demoUser.niche
        .replace(/-/g, ' ')
        .replace(/\b\w/g, char => char.toUpperCase())
    : 'Service';
  const ownerLabel = demoUser.firstName ? `Owner: ${demoUser.firstName}` : 'Owner: Local Operator';

  return `
    <li><img src="${assetBase}/shared/assets/icons/lucide/badge-check.svg" class="lucide-icon lucide-icon-sm" alt=""> Verified via Live Jobs</li>
    <li><img src="${assetBase}/shared/assets/icons/lucide/building.svg" class="lucide-icon lucide-icon-sm" alt=""> ${businessName}</li>
    <li><img src="${assetBase}/shared/assets/icons/lucide/briefcase.svg" class="lucide-icon lucide-icon-sm" alt=""> ${nicheLabel}</li>
    <li><img src="${assetBase}/shared/assets/icons/lucide/user.svg" class="lucide-icon lucide-icon-sm" alt=""> ${ownerLabel}</li>
    <li><img src="${assetBase}/shared/assets/icons/lucide/activity.svg" class="lucide-icon lucide-icon-sm" alt=""> Status: Active</li>
    <li><img src="${assetBase}/shared/assets/icons/lucide/clock.svg" class="lucide-icon lucide-icon-sm" alt=""> Last verified job: Just now</li>
    <li><img src="${assetBase}/shared/assets/icons/lucide/trending-up.svg" class="lucide-icon lucide-icon-sm" alt=""> Ranking improves with continued job activity</li>
  `;
}

function unlockDirectoryButton() {
  const btn = document.getElementById('btnViewDirectory');
  if (!btn) return;

  btn.classList.remove('is-hidden');
  btn.classList.add('dir-unlock');

  // Remove animation class after it runs once
  setTimeout(() => {
    btn.classList.remove('dir-unlock');
  }, 900);
}

async function sendReviewRequest() {
  // Remove empty state
  document.getElementById('review-empty')?.remove();

  // Metrics
  state.metrics.reviews++;
  safeText('metric-reviews', String(state.metrics.reviews));

  pulse('sim-review');

  document.getElementById('feed-review')?.insertAdjacentHTML('afterbegin', `
    <div class="feed-card">
      <div class="feed-image"><img src="${demoPhotos[0]}" alt="Job"></div>
      <div class="feed-content">
        <h4>Review Request Sent</h4>
        <p>SMS sent automatically</p>
      </div>
    </div>
  `);

  await wait(1100);

  pulse('sim-google');
  document.getElementById('feed-google')?.insertAdjacentHTML('afterbegin', `
    <div class="feed-card">
      <div class="feed-image"><img src="${demoPhotos[0]}" alt="Job"></div>
      <div class="feed-content">
        <h4>⭐ 5-Star Review Received</h4>
        <p>"Great service!"</p>
      </div>
    </div>
  `);

  // Unlock all simulator interactions
  lockBackButtons(false);
  document.querySelectorAll('.is-disabled').forEach(el => {
    el.classList.remove('is-disabled');
    el.disabled = false;
  });

  // Unlock directory CTA with animation
  unlockDirectoryButton();

  // Re-anchor tour
  updateTourFloating();

  // Return to home state
  setScreen('home-screen');
  // Advance to Directory step
  setTourStep('step6');

  renderHomeCheckins();

  // Update top CTA
  const headerCta = document.getElementById('btnNext');
  if (headerCta) {
    headerCta.textContent = 'Get Started →';
    headerCta.onclick = () => { window.location.href = (baseUrl || window.location.origin).replace(/\/?$/, '') + '/early-access'; };
  }

  // Reveal header "View Demo Directory" button AFTER demo finishes
  const btnViewDir = document.getElementById('btnViewDirectory');
  if (btnViewDir) {
    btnViewDir.classList.remove('is-hidden');
    btnViewDir.onclick = openDirectoryProfileFromDemo;
  }

  // Let them digest, then show post-demo panel
  setTimeout(() => {
    showPostDemoPanel();
  }, 900);
}


/* =========================================================
   Edit Existing Check-in
========================================================= */

function openCheckinForEdit(index) {
  const checkin = state.savedCheckins[index];
  if (!checkin) return;

  state.activeCheckinIndex = index;

  const descriptionField = $('description-field');
  if (descriptionField && checkin.summary) {
    descriptionField.value = checkin.summary;
  }

  const editGrid = $('edit-photo-grid');
  if (editGrid) {
    editGrid.innerHTML = '';
    if (checkin.image) {
      const photoDiv = document.createElement('div');
      photoDiv.className = 'photo-item';
      photoDiv.innerHTML = `<img src="${checkin.image}" alt="Job photo">`;
      editGrid.appendChild(photoDiv);
    }
  }

  // Status pill to Published
  const status = document.querySelector('.status-pill');
  if (status) status.innerHTML = `<img src="${assetBase}/shared/assets/icons/lucide/badge-check.svg" class="lucide-icon lucide-icon-sm" alt=""> Published`;

  setScreen('edit-screen');
}

/* =========================================================
   Unified Advance (Desktop + Mobile)
========================================================= */

function advanceDemo() {
  switch (tour.stepKey) {
    case 'step1':
      goToHome();
      setTourStep('step2');
      break;

    case 'step2':
      goToNew();
      setTourStep('step3');
      break;

    case 'step3':
      if (state.photoCount === 0) addPhotos();
      processPhotos();
      break;

    case 'step4':
      saveCheckin();
      break;

    case 'step5':
      sendReviewRequest();
      break;

    case 'step6':
      openDirectoryProfileFromDemo();
      break;

    default:
      return;
  }
}

function restartTour() {
  // Hard reset = cleanest, safest restart
  window.location.reload();
}


/* =========================================================
   Controls
========================================================= */

function wireControls() {
  $('btnReset')?.addEventListener('click', () => location.reload());
  $('#mobileBtnReset')?.addEventListener('click', () => location.reload());

  $('btnRestartLead')?.addEventListener('click', () => location.reload());

  $('btnNext')?.addEventListener('click', () => advanceDemo());
  $('#mobileBtnNext')?.addEventListener('click', () => advanceDemo());

  $('btnViewDirectory')?.addEventListener('click', openDirectoryProfileFromDemo);

  $('btnExit')?.addEventListener('click', () => {
    setScreen('login-screen');
    state.activeCheckinIndex = null;
  });
  $('#mobileBtnExit')?.addEventListener('click', () => {
    setScreen('login-screen');
    state.activeCheckinIndex = null;
  });

  $('btnMobileNext')?.addEventListener('click', () => advanceDemo());

  $('btnStartDemo')?.addEventListener('click', () => {
    $('btnStartDemo')?.classList.remove('wiggle-attention');
    goToHome();
    setTourStep('step2');
    applyFocalPoint();
    updateTourFloating();
  });

  $('fabNewCheckin')?.addEventListener(
    'click',
    () => {
      $('fabNewCheckin')?.classList.remove('fab-attention', 'fab-glow');
    },
    { once: true }
  );

  $('tour-close')?.addEventListener('click', closeTour);
  $('tour-minimize')?.addEventListener('click', minimizeTour);
  $('tour-bubble')?.addEventListener('click', () => {
    if (state.isFinalStep) return; // HARD STOP on final step
    tour.isHidden = false;
    tour.isMinimized = false;
    showTour();
  });
  $('tour-next')?.addEventListener('click', () => advanceDemo());

  window.addEventListener('resize', updateTourFloating, { passive: true });
  window.addEventListener('scroll', updateTourFloating, { passive: true });

  document.querySelectorAll('.content-area').forEach(el => {
    el.addEventListener('scroll', updateTourFloating, { passive: true });
  });

document
  .getElementById('btnLoadSampleData')
  ?.addEventListener('click', loadSampleCheckins);

}

/* =========================================================
   INIT
========================================================= */

function init() {
  initializeWebsite();
  applyPersonalization();

  // Mobile mode
  applyMobileMode();
  window.addEventListener('resize', applyMobileMode);

  // Greeting
  const greeting = document.querySelector('.greeting');
  if (greeting) {
    greeting.innerHTML = `
      Hi, <span class="greeting-accent">${demoUser.firstName}</span> | ${demoUser.businessName}
    `;
  }

  // Wire UI
  wireControls();
  wirePostDemoPanel();

  applyFocalPoint();
  syncAttentionAnimations();

  const returnState = readReturnState();
  if (returnState) {
    if (returnState.hasPublished) {
      state.hasPublished = true;
    }
    if (returnState.guideDisabled) {
      state.guideDisabled = true;
    }
    if (returnState.screenId) {
      setScreen(returnState.screenId);
    }
    if (returnState.stepKey) {
      setTourStep(returnState.stepKey);
    }
    
    // Only show tour if guide is not disabled
    // (when returning from directory, guide is disabled and tooltip should be hidden)
    if (!state.guideDisabled) {
      showTour();
      updateTourFloating();
    } else {
      // Ensure tour elements are hidden when guide is disabled
      document.getElementById('tour-float')?.classList.add('is-hidden');
      document.getElementById('tour-bubble')?.classList.add('is-hidden');
    }
    
    if (returnState.showPostDemoPanel) {
      showPostDemoPanel();
    }
    clearReturnState();
    return;
  }

  // Tour start (after DOM paints)
  setTimeout(() => {
    setTourStep('step1');
    showTour();
    updateTourFloating();
  }, 50);
}

function updateTourNextLabel(label = 'Next →') {
  const btn = $('tour-next');
  if (btn) btn.textContent = label;
}

function readReturnState() {
  try {
    const raw = localStorage.getItem('demoReturnState');
    return raw ? JSON.parse(raw) : null;
  } catch (e) {
    return null;
  }
}

function clearReturnState() {
  try {
    localStorage.removeItem('demoReturnState');
  } catch (e) {
    // no-op
  }
}

function openDirectoryProfileFromDemo() {
  // Final hard stop of tour
  tour.isHidden = true;
  tour.isMinimized = true;
  state.guideDisabled = true;
  tour.stepKey = null;

  document.getElementById('tour-float')?.classList.add('is-hidden');
  document.getElementById('tour-bubble')?.classList.add('is-hidden');

  localStorage.setItem(
    'directoryDemoListing',
    JSON.stringify({
      businessName: demoUser.businessName,
      status: 'Active',
      lastJob: 'Just now'
    })
  );

  localStorage.setItem(
    'demoReturnState',
    JSON.stringify({
      screenId: state.currentScreen,
      stepKey: 'step6',
      showPostDemoPanel: true,
      guideDisabled: true,
      hasPublished: state.hasPublished
    })
  );

  window.location.href = `${baseUrl}/company/?id=contractor-demo`;
}

/* =========================================================
   FINAL POST-DEMO SALES STEP
========================================================= */

function showPostDemoPanel() {
  const panel = document.getElementById('post-demo-panel');
  if (!panel) return;

  const bubble = document.getElementById('post-demo-bubble');
  bubble?.classList.add('is-hidden');

  panel.classList.add('active');

  const navBtn = document.getElementById('dynamicBackBtn');
  if (navBtn) {
    navBtn.style.display = 'inline-flex';
    navBtn.setAttribute('href', `${baseUrl}/company/?id=contractor-demo`);
    const label = navBtn.querySelector('span');
    if (label) {
      label.textContent = 'View Your Directory Listing';
    }
    const icon = navBtn.querySelector('svg');
    if (icon) {
      icon.innerHTML = '<path d="M12 22s8-4 8-10a8 8 0 1 0-16 0c0 6 8 10 8 10Z"/><circle cx="12" cy="12" r="3"/>';
    }
  }

  const mobileNavBtn = document.getElementById('mobileDynamicBackBtn');
  if (mobileNavBtn) {
    mobileNavBtn.style.display = 'inline-flex';
    mobileNavBtn.setAttribute('href', `${baseUrl}/company/?id=contractor-demo`);
    const label = mobileNavBtn.querySelector('span');
    if (label) {
      label.textContent = 'View Your Directory Listing';
    }
    const icon = mobileNavBtn.querySelector('svg');
    if (icon) {
      icon.innerHTML = '<path d="M12 22s8-4 8-10a8 8 0 1 0-16 0c0 6 8 10 8 10Z"/><circle cx="12" cy="12" r="3"/>';
    }
  }

  // Keep final tour tooltip visible above modal
  if (state.isFinalStep) {
    tour.isHidden = false;
    tour.isMinimized = false;

    const tourEl = document.getElementById('tour-float');
    if (tourEl) tourEl.classList.remove('is-hidden');

    // Force re-anchor under "View Demo Directory"
    requestAnimationFrame(() => {
      updateTourFloating();
    });
  }

  // Remove dimming
  document.querySelectorAll('.is-dimmed').forEach(el => {
    el.classList.remove('is-dimmed');
  });

  // Close on overlay click
  panel.addEventListener('click', postDemoOverlayClose);

  // Close on ESC
  document.addEventListener('keydown', postDemoEscClose);

  document.getElementById('directory-hint')?.classList.remove('is-hidden');

}

function hidePostDemoPanel() {
  const panel = document.getElementById('post-demo-panel');
  const bubble = document.getElementById('post-demo-bubble');
  if (!panel || !bubble) return;

  panel.classList.remove('active');
  bubble.classList.remove('is-hidden');

  panel.removeEventListener('click', postDemoOverlayClose);
  document.removeEventListener('keydown', postDemoEscClose);
  const dir = document.getElementById('directory-collapsible');
  if (dir) dir.style.display = 'none';
  document.getElementById('directory-hint')?.classList.add('is-hidden');
}

function postDemoOverlayClose(e) {
  if (e.target.id === 'post-demo-panel') {
    hidePostDemoPanel();
  }
}

function postDemoEscClose(e) {
  if (e.key === 'Escape') {
    hidePostDemoPanel();
  }
}


/* ----------------------------------
   Email demo link (basic stub)
---------------------------------- */
function emailDemoLink() {
  if (!demoUser.email) {
    alert('We don’t have an email on file for this demo.');
    return;
  }

  fetch('/api/email-demo', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      email: demoUser.email,
      firstName: demoUser.firstName,
      businessName: demoUser.businessName,
      demoUrl: window.location.href
    })
  });

  const btn = document.getElementById('postDemoEmailBtn');
  if (btn) {
    btn.textContent = 'Demo sent ✓';
    btn.disabled = true;
    btn.classList.add('is-disabled');
  }
}



/* ----------------------------------
   Bind panel buttons
---------------------------------- */
function wirePostDemoPanel() {
  document
    .getElementById('postDemoX')
    ?.addEventListener('click', hidePostDemoPanel);

  document
    .getElementById('post-demo-bubble')
    ?.addEventListener('click', showPostDemoPanel);

  document
    .getElementById('viewDirectoryFromSales')
    ?.addEventListener('click', openDirectoryProfileFromDemo);
}


init();

/* =========================================================
   GLOBALS (required because HTML uses inline onclick)
   Keep these on window so nothing breaks.
========================================================= */
window.goToHome = goToHome;
window.goToNew = goToNew;
window.addPhotos = addPhotos;
window.processPhotos = processPhotos;
window.regenerateDescription = regenerateDescription;
window.saveCheckin = saveCheckin;
window.openReviewDialog = openReviewDialog;
window.closeReviewDialog = closeReviewDialog;
window.sendReviewRequest = sendReviewRequest;
window.openCheckinForEdit = openCheckinForEdit;
window.advanceDemo = advanceDemo;
