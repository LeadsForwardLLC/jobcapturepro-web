(() => {
  const header = document.getElementById('jcpGlobalHeader');
  if (!header) return;

  const menuToggle = document.getElementById('mobileMenuToggle');
  const menuClose = document.getElementById('mobileMenuClose');
  const menuOverlay = document.getElementById('mobileMenuOverlay');

  const initMobileMenu = () => {
    if (!menuToggle || !menuClose || !menuOverlay) return;

    const closeMenu = () => {
      menuOverlay.classList.remove('active');
      menuToggle.classList.remove('active');
      document.body.style.overflow = '';
    };

    menuToggle.addEventListener('click', () => {
      menuOverlay.classList.add('active');
      menuToggle.classList.add('active');
      document.body.style.overflow = 'hidden';
    });

    menuClose.addEventListener('click', closeMenu);
    menuOverlay.addEventListener('click', (e) => {
      if (e.target === menuOverlay) {
        closeMenu();
      }
    });

    document.querySelectorAll('.mobile-nav-link').forEach((link) => {
      link.addEventListener('click', () => closeMenu());
    });

    const actionsTop = document.getElementById('mobileMenuActionsTop');
    if (actionsTop) {
      actionsTop.querySelectorAll('a, button').forEach((el) => {
        el.addEventListener('click', () => closeMenu());
      });
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && menuOverlay.classList.contains('active')) {
        closeMenu();
      }
    });
  };

  const initResourcesDropdown = () => {
    const trigger = document.getElementById('navResourcesTrigger');
    const menu = document.getElementById('navResourcesMenu');
    const dropdown = document.getElementById('navResourcesDropdown');
    if (!trigger || !menu || !dropdown) return;

    const open = () => {
      trigger.setAttribute('aria-expanded', 'true');
      menu.removeAttribute('hidden');
    };

    const close = () => {
      trigger.setAttribute('aria-expanded', 'false');
      menu.setAttribute('hidden', '');
      trigger.focus();
    };

    const isOpen = () => trigger.getAttribute('aria-expanded') === 'true';

    let hoverTimeout = null;

    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      if (isOpen()) {
        close();
      } else {
        open();
      }
    });

    dropdown.addEventListener('mouseenter', () => {
      if (hoverTimeout) clearTimeout(hoverTimeout);
      hoverTimeout = setTimeout(open, 200);
    });

    dropdown.addEventListener('mouseleave', () => {
      if (hoverTimeout) clearTimeout(hoverTimeout);
      hoverTimeout = setTimeout(() => {
        if (isOpen()) close();
      }, 150);
    });

    document.addEventListener('click', (e) => {
      if (isOpen() && !dropdown.contains(e.target)) {
        close();
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      if (isOpen()) {
        e.preventDefault();
        close();
      }
    });

    const items = menu.querySelectorAll('[role="menuitem"]');
    items.forEach((item, i) => {
      item.addEventListener('click', () => close());
      item.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowDown' && i < items.length - 1) {
          e.preventDefault();
          items[i + 1].focus();
        } else if (e.key === 'ArrowUp' && i > 0) {
          e.preventDefault();
          items[i - 1].focus();
        } else if (e.key === 'Escape') {
          e.preventDefault();
          close();
        }
      });
    });

    trigger.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        trigger.click();
      }
      if (e.key === 'ArrowDown' && isOpen() && items.length) {
        e.preventDefault();
        items[0].focus();
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
      if (isCompany && badge.textContent.trim() !== 'Contractor Profile (coming soon)') {
        badge.textContent = 'Contractor Profile (coming soon)';
      } else if (isDirectory && badge.textContent.trim() !== 'Directory (coming soon)') {
        badge.textContent = 'Directory (coming soon)';
      } else if (isDemo && badge.textContent.trim() !== 'Interactive Demo') {
        badge.textContent = 'Interactive Demo';
      }
    }
    if (mobileBadge) {
      mobileBadge.classList.toggle('is-hidden', !isDemoPage);
      if (isCompany && mobileBadge.textContent.trim() !== 'Contractor Profile (coming soon)') {
        mobileBadge.textContent = 'Contractor Profile (coming soon)';
      } else if (isDirectory && mobileBadge.textContent.trim() !== 'Directory (coming soon)') {
        mobileBadge.textContent = 'Directory (coming soon)';
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
      document.querySelectorAll('.nav-link[href="/pricing"]').forEach((link) => link.classList.add('is-active'));
      document.querySelectorAll('.mobile-nav-link[href="/pricing"]').forEach((link) => link.classList.add('is-active'));
    }
  };

  initMobileMenu();
  initScroll();
  initNavLinks();
  initResourcesDropdown();
})();
