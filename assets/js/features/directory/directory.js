/* =========================================================
   DIRECTORY PROTOTYPE (HANDOFF-READY)
   Purpose:
   - Simulates JobCapturePro contractor rankings
   - Documents future ranking logic for API integration
========================================================= */


const assetBase = window.JCP_ASSET_BASE || '';


/* =========================================================
   DOM ELEMENTS
========================================================= */

const grid = document.getElementById("directoryGrid");
const searchInput = document.getElementById("searchInput");
const cityFilter = document.getElementById("cityFilter");
const serviceFilter = document.getElementById("serviceFilter");
const verifiedOnlyToggle = document.getElementById("verifiedOnly");
const loadMoreBtn = document.getElementById("loadMore");
const sortBySelect = document.getElementById("sortBy");
const clearSearchBtn = document.getElementById("clearSearch");
const clearFiltersBtn = document.getElementById("clearFiltersBtn");
const resultsCount = document.getElementById("resultsCount");

const baseUrl = window.JCP_CONFIG && window.JCP_CONFIG.baseUrl
  ? window.JCP_CONFIG.baseUrl
  : window.location.origin;

/* =========================================================
   STATE
========================================================= */

const allListings = Array.isArray(window.JCP_DIRECTORY_DATA?.listings)
  ? window.JCP_DIRECTORY_DATA.listings
  : [];
let visibleCount = 6;
let filteredListings = [...allListings];

function getDemoListing() {
  try {
    const demoUser = JSON.parse(localStorage.getItem('demoUser') || 'null');
    if (!demoUser) return null;

    return {
      id: 'demo-listing',
      name: demoUser.businessName || 'Your Business',
      service: demoUser.niche || 'Service',
      city: demoUser.serviceArea || 'Service Area',
      rating: '5.0',
      reviews: 'New',
      jobs: 1,
      badge: 'verified',
      lastJobDaysAgo: 0,
      isDemo: true,
      permalink: `${baseUrl}/company/?id=contractor-demo`
    };
  } catch (e) {
    return null;
  }
}


/* =========================================================
   EVENT BINDINGS
========================================================= */

[
  searchInput,
  cityFilter,
  serviceFilter,
  verifiedOnlyToggle,
  sortBySelect
].forEach(el =>
  el.addEventListener("input", () => {
    visibleCount = 6;
    render();
  })
);

loadMoreBtn.addEventListener("click", () => {
  visibleCount += 6;
  renderCards();
});

// Clear search button
if (clearSearchBtn) {
  clearSearchBtn.addEventListener("click", () => {
    searchInput.value = "";
    clearSearchBtn.classList.add("is-hidden");
    visibleCount = 6;
    render();
  });
}

// Show/hide clear search button
if (searchInput) {
  searchInput.addEventListener("input", () => {
    if (searchInput.value.length > 0) {
      clearSearchBtn?.classList.remove("is-hidden");
    } else {
      clearSearchBtn?.classList.add("is-hidden");
    }
  });
}


// Clear all filters
if (clearFiltersBtn) {
  clearFiltersBtn.addEventListener("click", () => {
    searchInput.value = "";
    cityFilter.value = "";
    serviceFilter.value = "";
    sortBySelect.value = "activity";
    verifiedOnlyToggle.checked = false;
    clearSearchBtn?.classList.add("is-hidden");
    clearFiltersBtn.classList.add("is-hidden");
    visibleCount = 6;
    render();
  });
}


/* =========================================================
   CORE RENDER PIPELINE
========================================================= */

function render() {
  const term = (searchInput.value || "").trim();
  const termLower = term.toLowerCase();
  const city = cityFilter.value;
  const service = serviceFilter.value;
  const verifiedOnly = verifiedOnlyToggle.checked;

  /* ---------------------------------------------
     FILTERING
     ---------------------------------------------
     Advanced search: when user types (e.g. "carpet cleaning houston"),
     every word must match somewhere in name, service, or city.
     When search is empty, use dropdown filters only.
     Future: move to API query params
  */
  filteredListings = allListings.filter(l => {
    if (verifiedOnly && (l.badge === "listed" || l.badge === "unlisted")) return false;

    if (termLower) {
      const keywords = termLower.split(/\s+/).filter(Boolean);
      const searchable = [l.name, l.service, l.city].filter(Boolean).join(" ").toLowerCase();
      if (!keywords.every((kw) => searchable.includes(kw))) return false;
    } else {
      if (city && l.city !== city) return false;
      if (service && l.service !== service) return false;
    }

    return true;
  });

  /* ---------------------------------------------
     SORTING
     ---------------------------------------------
     Future:
     - "activity" = city rank score
     - computed server-side
  */
  const sortBy = sortBySelect.value;

  if (sortBy === "jobs") {
    filteredListings.sort((a, b) => (b.jobs || 0) - (a.jobs || 0));
  }

  if (sortBy === "rating") {
    filteredListings.sort((a, b) => (parseFloat(b.rating) || 0) - (parseFloat(a.rating) || 0));
  }

  const demoListing = getDemoListing();
  if (demoListing) {
    filteredListings = filteredListings.filter(l => l.id !== demoListing.id && l.name !== demoListing.name);
    filteredListings.unshift(demoListing);
  }

  // Update results count
  updateResultsCount();
  
  // Show/hide clear filters button
  updateClearFiltersButton(term, city, service, verifiedOnly);

  renderCards();
}

function updateResultsCount() {
  if (!resultsCount) return;
  
  const count = filteredListings.length;
  const countNumber = resultsCount.querySelector('.count-number');
  
  if (countNumber) {
    countNumber.textContent = count;
  }
  
  const plural = count === 1 ? 'contractor' : 'contractors';
  resultsCount.innerHTML = `<span class="count-number">${count}</span> ${plural} found`;
}

function updateClearFiltersButton(term, city, service, verifiedOnly) {
  if (!clearFiltersBtn) return;
  
  const hasActiveFilters = term || city || service || verifiedOnly;
  
  if (hasActiveFilters) {
    clearFiltersBtn.classList.remove('is-hidden');
  } else {
    clearFiltersBtn.classList.add('is-hidden');
  }
}


/* =========================================================
   LOGO QUALITY CHECK
========================================================= */

function isValidLogo(url) {
  if (!url || typeof url !== 'string' || url.trim() === '') return false;
  
  // Check for known placeholder patterns
  const lowerUrl = url.toLowerCase();
  if (lowerUrl.includes('placeholder') || 
      lowerUrl.includes('default') || 
      lowerUrl.includes('blank')) {
    return false;
  }
  
  // Check for valid image extensions
  const validExtensions = ['.png', '.jpg', '.jpeg', '.webp', '.svg'];
  return validExtensions.some(ext => lowerUrl.endsWith(ext));
}

function getCompanyInitial(name) {
  if (!name || typeof name !== 'string') return '?';
  return name.charAt(0).toUpperCase();
}

function getAvatarColor(initial) {
  const colors = [
    'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)', // Indigo
    'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)', // Blue
    'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)', // Violet
    'linear-gradient(135deg, #14b8a6 0%, #0d9488 100%)', // Teal
    'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)', // Amber
    'linear-gradient(135deg, #ec4899 0%, #db2777 100%)'  // Pink
  ];
  
  // Use char code to select color consistently
  const charCode = initial.charCodeAt(0);
  const index = charCode % colors.length;
  return colors[index];
}

function renderCompanyMark(listing) {
  const initial = getCompanyInitial(listing.name);
  const avatarColor = getAvatarColor(initial);
  
  if (isValidLogo(listing.logo)) {
    return `
      <div class="company-mark">
        <img 
          src="${listing.logo}" 
          alt="${listing.name}" 
          class="company-logo"
          onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        />
        <div class="company-avatar" style="display:none; background:${avatarColor};">${initial}</div>
      </div>
    `;
  } else {
    return `
      <div class="company-mark">
        <div class="company-avatar" style="background:${avatarColor};">${initial}</div>
      </div>
    `;
  }
}

/* =========================================================
   CARD RENDERING
========================================================= */

function renderCards() {
  grid.innerHTML = "";

  if (!filteredListings.length) {
    grid.innerHTML = `
      <div class="empty-state jcp-card" style="grid-column: 1 / -1; text-align: center; padding: var(--jcp-space-5xl);">
        <h3 style="font-size: var(--jcp-font-size-xl); font-weight: var(--jcp-font-weight-bold); margin-bottom: var(--jcp-space-md); color: var(--jcp-color-text-primary);">No contractors match your filters</h3>
        <p style="color: var(--jcp-color-text-secondary); margin: 0;">Try adjusting your search or filters to see more results.</p>
      </div>
    `;
    loadMoreBtn.style.display = "none";
    return;
  }

  filteredListings.slice(0, visibleCount).forEach(l => {

    /* ---------------------------------------------
       MOMENTUM LOGIC (PROTOTYPE)
       ---------------------------------------------
       Future replacement:
       - calculate from verified job timestamps
       - NOT user-editable
    */
    const lastJobDays = Number.isFinite(l.lastJobDaysAgo) ? l.lastJobDaysAgo : 0;
    const momentumClass =
      lastJobDays <= 3 ? "up"
      : lastJobDays <= 7 ? "steady"
      : "down";

    const momentumLabel =
      lastJobDays <= 3 ? "▲ Active"
      : lastJobDays <= 7 ? "● Stable"
      : "▼ Slowing";

    const card = document.createElement("a");
    card.href = l.permalink || `${baseUrl}/company/?id=${l.id}`;
    card.className = `jcp-card directory-card${l.isDemo ? ' is-demo' : ''}`;

    const lastJobText =
      lastJobDays === 0 ? "recently"
      : lastJobDays === 1 ? "1 day ago"
      : lastJobDays === 2 ? "2 days ago"
      : `${lastJobDays} days ago`;

    card.innerHTML = `
      ${l.isDemo ? '<span class="demo-flag">Demo Listing</span>' : ''}
      <span class="directory-badge ${l.badge || 'listed'}">
        ${
          l.badge === "trusted"
            ? "Trusted Pro"
            : l.badge === "verified"
            ? "Verified"
            : l.badge === "unlisted"
            ? "Unlisted"
            : "Listed"
        }
      </span>
      
      <div class="card-header">
        ${renderCompanyMark(l)}
        <div class="card-header-content">
          <h3 class="card-name">${l.name}</h3>
        </div>
      </div>
      
      <div class="card-location">
        <img src="${assetBase}/shared/assets/icons/lucide/map-pin.svg" class="lucide-icon lucide-icon-xs" alt="">
        <span>${l.city || 'Service Area'}</span>
      </div>

      <div class="card-meta-row">
        <span class="meta-inline">
          <img src="${assetBase}/shared/assets/icons/lucide/camera.svg" class="lucide-icon lucide-icon-xs" alt="">
          ${(l.jobs || 0)} jobs
        </span>
        <span class="meta-divider">·</span>
        <span class="meta-inline">
          <img src="${assetBase}/shared/assets/icons/lucide/clock.svg" class="lucide-icon lucide-icon-xs" alt="">
          Active ${lastJobText}
        </span>
      </div>

      <div class="card-rating">
        <div class="stars">★★★★★</div>
        <span class="rating-text">
          ${l.rating ? `${l.rating} (${l.reviews || 0})` : '—'}
        </span>
      </div>

      <div class="card-footer">
        <span class="view-profile">View activity</span>
      </div>
    `;

    grid.appendChild(card);
  });

  loadMoreBtn.style.display =
    visibleCount < filteredListings.length ? "block" : "none";
}


/* =========================================================
   VIEW TOGGLE
========================================================= */

const gridViewBtn = document.getElementById("gridView");
const listViewBtn = document.getElementById("listView");

/* Default view = grid */
grid.classList.remove("list-view");
gridViewBtn.classList.add("active");
listViewBtn.classList.remove("active");

gridViewBtn.addEventListener("click", () => {
  grid.classList.remove("list-view");
  gridViewBtn.classList.add("active");
  listViewBtn.classList.remove("active");
});

listViewBtn.addEventListener("click", () => {
  grid.classList.add("list-view");
  listViewBtn.classList.add("active");
  gridViewBtn.classList.remove("active");
});


/* =========================================================
   WORK SHOWCASE (Before/After Interactive)
========================================================= */

const placeholderImage = (label) =>
  `data:image/svg+xml;utf8,` +
  encodeURIComponent(
    `<svg xmlns="http://www.w3.org/2000/svg" width="800" height="520">
      <defs>
        <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0%" stop-color="#f3f4f6"/>
          <stop offset="100%" stop-color="#e5e7eb"/>
        </linearGradient>
      </defs>
      <rect width="100%" height="100%" fill="url(#g)"/>
      <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
        font-family="Arial" font-size="28" fill="#9ca3af">${label}</text>
    </svg>`
  );

const showcaseListings = (() => {
  const demoListing = getDemoListing();
  const base = demoListing
    ? [demoListing, ...allListings.filter(l => l.id !== demoListing.id && l.name !== demoListing.name)]
    : [...allListings];
  return base.slice(0, 4);
})();

const showcaseData = showcaseListings.map((listing) => {
  const days = Number.isFinite(listing.lastJobDaysAgo) ? listing.lastJobDaysAgo : 0;
  const status = days === 0 ? 'Completed recently' : `Completed ${days} days ago`;
  const badgeText = listing.badge === 'trusted'
    ? 'Trusted Pro'
    : listing.badge === 'verified'
      ? 'Verified'
      : listing.badge === 'unlisted'
        ? 'Unlisted'
        : 'Listed';

  return {
    before: listing.isDemo ? placeholderImage('Before') : (listing.beforeImage || placeholderImage('Before')),
    after: listing.isDemo ? placeholderImage('After') : (listing.afterImage || placeholderImage('After')),
    contractor: {
      name: listing.name || 'Contractor',
      avatar: listing.logo || '',
      badge: badgeText,
      rating: listing.rating || '—',
      status,
      trade: listing.service || 'Service'
    }
  };
});

let currentShowcaseIndex = 0;
let sliderPosition = 50;
let isDragging = false;

function initShowcase() {
  const showcase = document.getElementById('workShowcase');
  if (!showcase) return;
  if (!showcaseData.length) {
    showcase.classList.add('is-hidden');
    return;
  }

  const slider = document.getElementById('showcaseSlider');
  const afterEl = document.getElementById('showcaseAfter');
  const frame = showcase.querySelector('.showcase-frame');
  
  // Set initial showcase
  updateShowcaseContent(currentShowcaseIndex);
  
  // Slider interaction
  frame.addEventListener('mousedown', startDrag);
  frame.addEventListener('touchstart', startDrag, { passive: true });
  
  document.addEventListener('mousemove', drag);
  document.addEventListener('touchmove', drag, { passive: false });
  
  document.addEventListener('mouseup', stopDrag);
  document.addEventListener('touchend', stopDrag);
  
  // Dot navigation
  const dots = showcase.querySelectorAll('.dot');
  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      currentShowcaseIndex = index;
      updateShowcaseContent(index);
      updateDots(index);
    });
  });
  
  // Auto-cycle every 6 seconds
  setInterval(() => {
    if (!isDragging) {
      currentShowcaseIndex = (currentShowcaseIndex + 1) % showcaseData.length;
      updateShowcaseContent(currentShowcaseIndex);
      updateDots(currentShowcaseIndex);
    }
  }, 6000);
}

function startDrag(e) {
  isDragging = true;
  updateSliderPosition(e);
}

function drag(e) {
  if (!isDragging) return;
  e.preventDefault();
  updateSliderPosition(e);
}

function stopDrag() {
  isDragging = false;
}

function updateSliderPosition(e) {
  const frame = document.querySelector('.showcase-frame');
  if (!frame) return;
  
  const rect = frame.getBoundingClientRect();
  const clientX = e.touches ? e.touches[0].clientX : e.clientX;
  const x = clientX - rect.left;
  const percentage = Math.max(0, Math.min(100, (x / rect.width) * 100));
  
  sliderPosition = percentage;
  
  const slider = document.getElementById('showcaseSlider');
  const afterEl = document.getElementById('showcaseAfter');
  
  if (slider) slider.style.left = `${percentage}%`;
  if (afterEl) afterEl.style.clipPath = `inset(0 0 0 ${percentage}%)`;
}

function updateShowcaseContent(index) {
  const data = showcaseData[index];
  
  // Update images with loading attribute for performance
  const beforeImg = document.getElementById('showcaseBeforeImg');
  const afterImg = document.getElementById('showcaseAfterImg');
  
  if (beforeImg) {
    beforeImg.src = data.before;
    beforeImg.loading = 'eager'; // Load immediately for current slide
  }
  if (afterImg) {
    afterImg.src = data.after;
    afterImg.loading = 'eager';
  }
  
  // Preload next slide for smooth transition
  const nextIndex = (index + 1) % showcaseData.length;
  const nextData = showcaseData[nextIndex];
  
  const preloadBefore = new Image();
  const preloadAfter = new Image();
  preloadBefore.src = nextData.before;
  preloadAfter.src = nextData.after;
  
  // Update contractor profile
  document.getElementById('profileName').textContent = data.contractor.name;
  document.getElementById('profileBadge').textContent = data.contractor.badge;
  document.getElementById('profileRating').textContent = data.contractor.rating;
  document.getElementById('profileStatus').innerHTML = `
    <span class="status-dot"></span>
    ${data.contractor.status}
  `;
  
  // Update avatar
  const avatarEl = document.getElementById('profileAvatar');
  if (data.contractor.avatar) {
    avatarEl.src = data.contractor.avatar;
  }
  
  // Reset slider to center
  sliderPosition = 50;
  const slider = document.getElementById('showcaseSlider');
  const afterEl = document.getElementById('showcaseAfter');
  if (slider) slider.style.left = '50%';
  if (afterEl) afterEl.style.clipPath = 'inset(0 0 0 50%)';
}

function updateDots(index) {
  const dots = document.querySelectorAll('.showcase-dots .dot');
  dots.forEach((dot, i) => {
    dot.classList.toggle('active', i === index);
  });
}

/* =========================================================
   DYNAMIC NAVIGATION
========================================================= */

function initDynamicNavigation() {
  // Check if user came from demo
  const referrer = document.referrer;
  const cameFromDemo = referrer.includes('/jcp-demo/') || referrer.includes('/demo/');
  const returnState = localStorage.getItem('demoReturnState');
  
  const dynamicBackBtn = document.getElementById('dynamicBackBtn');
  const mobileDynamicBackBtn = document.getElementById('mobileDynamicBackBtn');
  
  if (cameFromDemo || returnState) {
    // User came from JCP demo - change button text
    if (dynamicBackBtn) {
      const textSpan = dynamicBackBtn.querySelector('span');
      if (textSpan) textSpan.textContent = 'Back to Demo';
    }
    if (mobileDynamicBackBtn) {
      const textSpan = mobileDynamicBackBtn.querySelector('span');
      if (textSpan) textSpan.textContent = 'Back to Demo';
    }
  }
}

/* =========================================================
   MOBILE MENU
========================================================= */

function initMobileMenu() {
  const menuToggle = document.getElementById('mobileMenuToggle');
  const menuClose = document.getElementById('mobileMenuClose');
  const menuOverlay = document.getElementById('mobileMenuOverlay');
  
  if (!menuToggle || !menuClose || !menuOverlay) return;
  
  // Open menu
  menuToggle.addEventListener('click', () => {
    menuOverlay.classList.add('active');
    menuToggle.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent background scroll
  });
  
  // Close menu
  const closeMenu = () => {
    menuOverlay.classList.remove('active');
    menuToggle.classList.remove('active');
    document.body.style.overflow = ''; // Restore scroll
  };
  
  menuClose.addEventListener('click', closeMenu);
  
  // Close when clicking overlay background
  menuOverlay.addEventListener('click', (e) => {
    if (e.target === menuOverlay) {
      closeMenu();
    }
  });
  
  // Close when clicking nav links (for anchor links)
  const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
  mobileNavLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      // Only close if it's an anchor link (starts with #)
      if (link.getAttribute('href').startsWith('#')) {
        closeMenu();
      }
    });
  });
  
  // Close on ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && menuOverlay.classList.contains('active')) {
      closeMenu();
    }
  });
}

/* =========================================================
   INIT
========================================================= */

initDynamicNavigation();
initMobileMenu();
initShowcase();
render();

// Rotating word in directory hero: work → reviews → activity → consistency (same pattern as homepage hero)
const rotatingWordEl = document.querySelector('.directory-shell .jcp-hero-rotating-word');
if (rotatingWordEl) {
  const words = ['work', 'reviews', 'activity'];
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
