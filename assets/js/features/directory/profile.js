/* =========================================================
   DYNAMIC CONTRACTOR PROFILE
   ---------------------------------------------------------
   Loads contractor data from URL params and displays
   profile information dynamically
========================================================= */

const assetBase = window.JCP_ASSET_BASE || '';

const CONTRACTOR_DATA = {
  badges: {
    trusted: { label: "Trusted Pro", color: "#f59e0b" },
    verified: { label: "Verified", color: "#3b82f6" },
    listed: { label: "Listed", color: "#6b7280" }
  }
};

/* =========================================================
   HELPER FUNCTIONS
========================================================= */


/* =========================================================
   LOAD CONTRACTOR DATA
========================================================= */

function toTitle(text) {
  return String(text || '')
    .replace(/[-_]+/g, ' ')
    .replace(/\b\w/g, (char) => char.toUpperCase())
    .trim();
}

function loadContractorData() {
  if (window.JCP_PROFILE_DATA) {
    return window.JCP_PROFILE_DATA;
  }

  try {
    const listing = JSON.parse(localStorage.getItem('directoryDemoListing') || 'null');
    const demoUser = JSON.parse(localStorage.getItem('demoUser') || 'null');

    if (listing || demoUser) {
      const service = toTitle(demoUser?.niche || 'Service');
      const city = demoUser?.serviceArea || 'Service Area';

      return {
        name: listing?.businessName || demoUser?.businessName || 'Your Business',
        service,
        city,
        badge: 'verified',
        rating: '5.0',
        reviews: 'New',
        activity: listing?.status || 'Active',
        jobs: 1,
        lastJobDaysAgo: 0,
        verifiedViaLiveJobs: true,
        checkins: []
      };
    }
  } catch (e) {
    // no-op
  }

  return null;
}

/* =========================================================
   RENDER PROFILE
========================================================= */

function renderProfile(data) {
  if (!data) return;
  
  // Update page title
  document.title = `${data.name} — Contractor Profile | JobCapturePro`;
  
  // Update badge
  const badgeEl = document.querySelector('.directory-badge');
  if (badgeEl) {
    const badgeKey = data.badge && CONTRACTOR_DATA.badges[data.badge] ? data.badge : 'listed';
    const badgeInfo = CONTRACTOR_DATA.badges[badgeKey];
    badgeEl.textContent = badgeInfo.label;
    badgeEl.className = `directory-badge ${badgeKey}`;
  }
  
  // Update name and meta
  const nameEl = document.querySelector('.profile-hero-copy .jcp-hero-title');
  if (nameEl) nameEl.textContent = data.name;
  
  const metaEl = document.querySelector('.profile-hero-copy .jcp-hero-subtitle');
  if (metaEl) metaEl.textContent = `${data.service || 'Service'} · ${data.city || 'Service Area'}`;
  
  // Show owner name if available
  if (data.owner) {
    const ownerEl = document.getElementById('profile-owner');
    if (ownerEl) {
      ownerEl.textContent = `Owner: ${data.owner}`;
      ownerEl.classList.remove('is-hidden');
    }
  }
  
  // Show verified badge if applicable
  if (data.verifiedViaLiveJobs) {
    const verifiedEl = document.getElementById('profile-verified');
    if (verifiedEl) verifiedEl.classList.remove('is-hidden');
  }
  
  // Update rating
  const reviewCountEl = document.querySelector('.review-count');
  if (reviewCountEl) {
    const rating = data.rating ? data.rating : '—';
    const reviews = data.reviews ? `${data.reviews} reviews` : 'No reviews yet';
    reviewCountEl.textContent = `${rating} (${reviews})`;
  }
  
  // Update recency
  const recencyEl = document.querySelector('.recency-note');
  if (recencyEl) {
    const days = Number.isFinite(data.lastJobDaysAgo) ? data.lastJobDaysAgo : 0;
    const timeText = days === 0 ? 'recently' :
                     days === 1 ? '1 day ago' :
                     days === 2 ? '2 days ago' :
                     `${days} days ago`;
    recencyEl.textContent = `Last verified job: ${timeText}`;
  }
  
  // Update stats
  const statsEl = document.querySelector('.stats');
  if (statsEl) {
    statsEl.innerHTML = `
      <span class="stat">${data.activity || 'Active'}</span>
      <span class="stat">${data.jobs || 0} jobs captured</span>
      <span class="stat">Active this week</span>
    `;
  }
  
  // Update description if there's a description section
  const descEl = document.querySelector('.profile-description');
  if (descEl && data.description) {
    descEl.textContent = data.description;
  }
  
  // Render check-ins
  renderCheckins(data.checkins || []);
}

function renderCheckins(checkins) {
  const checkinsGrid = document.getElementById('checkinsGrid');
  if (!checkinsGrid) return;

  if (!checkins.length) {
    checkinsGrid.innerHTML = `
      <div class="work-card">
        <div class="work-body">
          <h3>New check-ins coming soon</h3>
          <p>This contractor is active, and real job proof will appear here as check-ins sync.</p>
          <span class="meta">Updated automatically</span>
        </div>
      </div>
    `;
    return;
  }

  checkinsGrid.innerHTML = '';

  checkins.forEach(checkin => {
    const card = document.createElement('div');
    card.className = 'checkin-card';
    card.innerHTML = `
      <div class="checkin-image">
        <img src="${checkin.image}" alt="${checkin.title}" loading="lazy" />
      </div>
      <div class="checkin-content">
        <h3 class="checkin-title">${checkin.title}</h3>
        <p class="checkin-description">${checkin.description}</p>
        <div class="checkin-meta">
          <span class="checkin-time">
            <img src="${assetBase}/shared/assets/icons/lucide/clock.svg" class="lucide-icon lucide-icon-xs" alt="">
            ${checkin.time}
          </span>
          <span class="checkin-location">
            <img src="${assetBase}/shared/assets/icons/lucide/map-pin.svg" class="lucide-icon lucide-icon-xs" alt="">
            ${checkin.location}
          </span>
        </div>
      </div>
    `;
    checkinsGrid.appendChild(card);
  });
}

function showError(message) {
  const main = document.querySelector('main');
  if (main) {
    const baseUrl = window.JCP_CONFIG && window.JCP_CONFIG.baseUrl
      ? window.JCP_CONFIG.baseUrl
      : window.location.origin;
    main.innerHTML = `
      <div class="error-state">
        <h2>⚠️ ${message}</h2>
        <p>Please select a contractor from the directory.</p>
        <a href="${baseUrl}/directory/" class="btn btn-primary">Back to Directory</a>
      </div>
    `;
  }
}

/* =========================================================
   GALLERY FUNCTIONALITY
========================================================= */

function initGallery() {
  const gallery = document.getElementById('profileGallery');
  const track = gallery?.querySelector('.profile-gallery-track');
  const dotsContainer = document.getElementById('galleryDots');
  const prevBtn = document.querySelector('.gallery-prev');
  const nextBtn = document.querySelector('.gallery-next');
  
  if (!gallery || !track || !dotsContainer) return;
  
  const items = track.querySelectorAll('.profile-gallery-item');
  let currentIndex = 0;
  
  // Create dots
  items.forEach((_, index) => {
    const dot = document.createElement('button');
    dot.className = `gallery-dot ${index === 0 ? 'active' : ''}`;
    dot.setAttribute('aria-label', `Go to slide ${index + 1}`);
    dot.addEventListener('click', () => goToSlide(index));
    dotsContainer.appendChild(dot);
  });
  
  function updateGallery() {
    track.style.transform = `translateX(-${currentIndex * 100}%)`;
    
    // Update dots
    dotsContainer.querySelectorAll('.gallery-dot').forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });
    
    // Update button states
    if (prevBtn) prevBtn.disabled = currentIndex === 0;
    if (nextBtn) nextBtn.disabled = currentIndex === items.length - 1;
  }
  
  function goToSlide(index) {
    currentIndex = Math.max(0, Math.min(index, items.length - 1));
    updateGallery();
  }
  
  function nextSlide() {
    if (currentIndex < items.length - 1) {
      currentIndex++;
      updateGallery();
    }
  }
  
  function prevSlide() {
    if (currentIndex > 0) {
      currentIndex--;
      updateGallery();
    }
  }
  
  // Button handlers
  if (prevBtn) prevBtn.addEventListener('click', prevSlide);
  if (nextBtn) nextBtn.addEventListener('click', nextSlide);
  
  // Keyboard navigation
  gallery.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') prevSlide();
    if (e.key === 'ArrowRight') nextSlide();
  });
  
  // Touch/swipe support
  let startX = 0;
  let isDragging = false;
  
  gallery.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    isDragging = true;
  });
  
  gallery.addEventListener('touchmove', (e) => {
    if (!isDragging) return;
    e.preventDefault();
  });
  
  gallery.addEventListener('touchend', (e) => {
    if (!isDragging) return;
    isDragging = false;
    const endX = e.changedTouches[0].clientX;
    const diff = startX - endX;
    
    if (Math.abs(diff) > 50) {
      if (diff > 0) nextSlide();
      else prevSlide();
    }
  });
  
  // Auto-play (optional - can be disabled)
  // let autoPlayInterval = setInterval(nextSlide, 5000);
  // gallery.addEventListener('mouseenter', () => clearInterval(autoPlayInterval));
  // gallery.addEventListener('mouseleave', () => {
  //   autoPlayInterval = setInterval(nextSlide, 5000);
  // });
  
  updateGallery();
}

/* =========================================================
   INIT
========================================================= */

document.addEventListener('DOMContentLoaded', () => {
  const contractorData = loadContractorData();
  if (contractorData) {
    renderProfile(contractorData);
  } else {
    showError('Contractor data unavailable');
  }
  
  // Initialize gallery
  initGallery();
});
