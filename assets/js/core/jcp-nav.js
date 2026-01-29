(() => {
  const header = document.getElementById('jcpGlobalHeader');
  if (!header) return;

  const menuToggle = document.getElementById('mobileMenuToggle');
  const menuClose = document.getElementById('mobileMenuClose');
  const menuOverlay = document.getElementById('mobileMenuOverlay');

  const initMobileMenu = () => {
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
  };

  const initScroll = () => {
    const onScroll = () => {
      header.classList.toggle('is-scrolled', window.scrollY > 12);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  };

  const initNavLinks = () => {
    const root = document.getElementById('jcp-app');
    const page = root ? root.dataset.jcpPage : 'home';
    const isHome = page === 'home';
    const isDirectory = page === 'directory';
    const isCompany = page === 'company';
    const isDemo = page === 'demo';
    const isDemoPage = ['demo', 'directory', 'company'].includes(page);
    const badge = document.getElementById('jcpHeaderIndicator');
    const mobileBadge = document.getElementById('jcpMobileBadge');

    if (badge) {
      badge.classList.toggle('is-hidden', !isDemoPage);
      if (isCompany && badge.textContent.trim() !== 'Contractor Profile') {
        badge.textContent = 'Contractor Profile';
      } else if (isDirectory && badge.textContent.trim() !== 'Directory') {
        badge.textContent = 'Directory';
      } else if (isDemo && badge.textContent.trim() !== 'Interactive Demo') {
        badge.textContent = 'Interactive Demo';
      }
    }
    if (mobileBadge) {
      mobileBadge.classList.toggle('is-hidden', !isDemoPage);
      if (isCompany && mobileBadge.textContent.trim() !== 'Contractor Profile') {
        mobileBadge.textContent = 'Contractor Profile';
      } else if (isDirectory && mobileBadge.textContent.trim() !== 'Directory') {
        mobileBadge.textContent = 'Directory';
      } else if (isDemo && mobileBadge.textContent.trim() !== 'Interactive Demo') {
        mobileBadge.textContent = 'Interactive Demo';
      }
    }

    document.querySelectorAll('[data-home-anchor]').forEach((link) => {
      const anchor = link.getAttribute('data-home-anchor');
      if (!anchor) return;
      if (isDirectory && anchor === '#how-it-works') {
        // On directory page, link to the how-it-works section on the same page
        link.setAttribute('href', '#how-it-works');
      } else {
        link.setAttribute('href', isHome ? anchor : `/#${anchor.replace('#', '')}`);
      }
    });

    document.querySelectorAll('.nav-link').forEach((link) => {
      link.classList.remove('is-active');
    });
    document.querySelectorAll('.mobile-nav-link').forEach((link) => {
      link.classList.remove('is-active');
    });

    if (page === 'pricing') {
      document.querySelectorAll(`.nav-link[href="/pricing"]`).forEach((link) => link.classList.add('is-active'));
      document.querySelectorAll(`.mobile-nav-link[href="/pricing"]`).forEach((link) => link.classList.add('is-active'));
    }
  };

  initMobileMenu();
  initScroll();
  initNavLinks();
})();
