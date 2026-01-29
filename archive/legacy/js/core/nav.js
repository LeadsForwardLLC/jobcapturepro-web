(() => {
  const header = document.getElementById('jcpGlobalHeader');
  if (!header) return;

  const menuToggle = document.getElementById('mobileMenuToggle');
  const menuClose = document.getElementById('mobileMenuClose');
  const menuOverlay = document.getElementById('mobileMenuOverlay');

  const initMobileMenu = () => {
    if (!menuToggle || !menuClose || !menuOverlay) return;

    menuToggle.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      menuOverlay.classList.add('active');
      menuToggle.classList.add('active');
      document.body.style.overflow = 'hidden';
    });

    const closeMenu = (e) => {
      if (e) {
        e.preventDefault();
        e.stopPropagation();
      }
      menuOverlay.classList.remove('active');
      menuToggle.classList.remove('active');
      document.body.style.overflow = '';
    };

    menuClose.addEventListener('click', closeMenu);
    menuClose.addEventListener('touchstart', closeMenu);
    
    menuOverlay.addEventListener('click', (e) => {
      if (e.target === menuOverlay || e.target.closest('.mobile-menu-content') === null) {
        closeMenu(e);
      }
    });

    document.querySelectorAll('.mobile-nav-link').forEach((link) => {
      link.addEventListener('click', () => closeMenu());
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && menuOverlay.classList.contains('active')) {
        closeMenu(e);
      }
    });
  };

  const initScroll = () => {
    let ticking = false;
    const onScroll = () => {
      if (!ticking) {
        window.requestAnimationFrame(() => {
          const scrollY = window.scrollY || window.pageYOffset || document.documentElement.scrollTop;
          header.classList.toggle('is-scrolled', scrollY > 50);
          ticking = false;
        });
        ticking = true;
      }
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  };

  const initNavLinks = () => {
    const root = document.getElementById('jcp-app');
    const page = root ? root.dataset.jcpPage : 'home';
    const isHome = page === 'home';
    const isDemoPage = ['demo', 'directory', 'company', 'survey', 'estimate'].includes(page);
    const badge = document.getElementById('jcpHeaderIndicator');
    const mobileBadge = document.getElementById('jcpMobileBadge');
    const demoBtn = document.getElementById('dynamicBackBtn');
    const mobileDemoBtn = document.getElementById('mobileDynamicBackBtn');
    const demoIcon = document.getElementById('dynamicBackIcon');
    const mobileDemoIcon = document.getElementById('mobileDynamicBackIcon');

    const arrowIcon = '<path d="M5 12h14M13 5l7 7-7 7"/>';
    const directoryIcon = '<path d="M12 22s8-4 8-10a8 8 0 1 0-16 0c0 6 8 10 8 10Z"/><circle cx="12" cy="12" r="3"/>';

    const setNavButton = (label, href, icon) => {
      if (demoBtn) {
        demoBtn.style.display = '';
        demoBtn.setAttribute('href', href);
        const labelEl = demoBtn.querySelector('span');
        if (labelEl) labelEl.textContent = label;
      }
      if (mobileDemoBtn) {
        mobileDemoBtn.style.display = '';
        mobileDemoBtn.setAttribute('href', href);
        const labelEl = mobileDemoBtn.querySelector('span');
        if (labelEl) labelEl.textContent = label;
      }
      if (demoIcon) demoIcon.innerHTML = icon;
      if (mobileDemoIcon) mobileDemoIcon.innerHTML = icon;
    };

    const enablePostDemoReturn = () => {
      const hasDemoSession = !!localStorage.getItem('demoUser');
      if (!hasDemoSession) return;

      const returnToPostDemo = (e) => {
        e.preventDefault();
        try {
          localStorage.setItem('demoReturnState', JSON.stringify({
            screenId: 'edit-screen',
            stepKey: 'step6',
            showPostDemoPanel: true,
            guideDisabled: false,
            hasPublished: true
          }));
        } catch (err) {
          // no-op
        }
        window.location.href = '/demo/?mode=run';
      };

      if (demoBtn && !demoBtn.dataset.returnBound) {
        demoBtn.dataset.returnBound = 'true';
        demoBtn.addEventListener('click', returnToPostDemo);
      }
      if (mobileDemoBtn && !mobileDemoBtn.dataset.returnBound) {
        mobileDemoBtn.dataset.returnBound = 'true';
        mobileDemoBtn.addEventListener('click', returnToPostDemo);
      }
    };

    if (badge) {
      badge.classList.toggle('is-hidden', !isDemoPage);
    }
    if (mobileBadge) {
      mobileBadge.classList.toggle('is-hidden', !isDemoPage);
    }

    if (page === 'demo') {
      if (demoBtn) demoBtn.style.display = 'none';
      if (mobileDemoBtn) mobileDemoBtn.style.display = 'none';
    } else if (page === 'company') {
      setNavButton('View Directory', '/directory', directoryIcon);
    } else if (page === 'directory') {
      setNavButton('Online Demo', '/demo', arrowIcon);
      enablePostDemoReturn();
    } else {
      setNavButton('Online Demo', '/demo', arrowIcon);
    }

    document.querySelectorAll('[data-home-anchor]').forEach((link) => {
      const anchor = link.getAttribute('data-home-anchor');
      if (!anchor) return;
      link.setAttribute('href', isHome ? anchor : `/#${anchor.replace('#', '')}`);
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
