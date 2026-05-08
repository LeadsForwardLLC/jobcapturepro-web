(() => {
  const KEY = 'jcp_earlybird_banner_dismissed';
  const banner = document.getElementById('jcpEarlybirdBanner');
  if (!banner) return;

  const close = document.getElementById('jcpEarlybirdBannerClose');

  const dismissed = (() => {
    try {
      return window.sessionStorage ? window.sessionStorage.getItem(KEY) === '1' : false;
    } catch (e) {
      return false;
    }
  })();

  const hide = () => {
    banner.remove();
    try {
      document.body.classList.remove('has-top-banner');
    } catch (e) {}
  };

  if (dismissed) {
    hide();
    return;
  }

  if (!close) return;
  close.addEventListener('click', () => {
    try {
      window.sessionStorage && window.sessionStorage.setItem(KEY, '1');
    } catch (e) {}
    hide();
  });
})();

