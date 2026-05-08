(() => {
  const KEY = 'jcp_earlybird_banner_dismissed_until';
  const banner = document.getElementById('jcpEarlybirdBanner');
  if (!banner) return;

  const close = document.getElementById('jcpEarlybirdBannerClose');

  const now = Date.now();
  const dismissedUntil = (() => {
    try {
      const raw = window.localStorage ? window.localStorage.getItem(KEY) : null;
      const n = raw ? Number(raw) : 0;
      return Number.isFinite(n) ? n : 0;
    } catch (e) {
      return 0;
    }
  })();

  const hide = () => {
    banner.remove();
    try {
      document.body.classList.remove('has-top-banner');
    } catch (e) {}
  };

  if (dismissedUntil && dismissedUntil > now) {
    hide();
    return;
  }

  if (!close) return;
  close.addEventListener('click', () => {
    try {
      const sevenDays = 7 * 24 * 60 * 60 * 1000;
      window.localStorage && window.localStorage.setItem(KEY, String(Date.now() + sevenDays));
    } catch (e) {}
    hide();
  });
})();

