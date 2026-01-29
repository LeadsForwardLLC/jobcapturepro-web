(() => {
  const root = document.getElementById('jcp-app');
  if (!root) return;

  const page = root.dataset.jcpPage;

  if (!window.JCP_ASSET_BASE) {
    const scriptSrc = document.currentScript && document.currentScript.src ? document.currentScript.src : '';
    const marker = '/core/jcp-render.js';
    if (scriptSrc.includes(marker)) {
      window.JCP_ASSET_BASE = scriptSrc.split(marker)[0];
    }
  }

  const assetBase = window.JCP_ASSET_BASE || '';
  const baseUrl = window.JCP_CONFIG && window.JCP_CONFIG.baseUrl
    ? window.JCP_CONFIG.baseUrl
    : window.location.origin;

  let templateUrl = '';

  switch (page) {
    case 'home':
      if (typeof window.renderHome === 'function') {
        window.renderHome();
        return;
      }
      console.warn('JCP render: renderHome is not available');
      return;
    case 'pricing':
      if (typeof window.renderPricing === 'function') {
        window.renderPricing();
        return;
      }
      console.warn('JCP render: renderPricing is not available');
      return;
    case 'early-access':
      if (typeof window.renderEarlyAccess === 'function') {
        window.renderEarlyAccess();
        return;
      }
      console.warn('JCP render: renderEarlyAccess is not available');
      return;
    case 'demo':
      if (new URLSearchParams(window.location.search).get('mode') === 'run') {
        templateUrl = `${assetBase}/demo/index.html`;
      } else {
        templateUrl = `${assetBase}/survey/index.html`;
      }
      break;
    case 'directory':
      templateUrl = `${assetBase}/directory/index.html`;
      break;
    case 'estimate':
      templateUrl = `${assetBase}/estimate/index.html`;
      break;
    case 'company':
      templateUrl = `${assetBase}/directory/profile.html`;
      break;
    default:
      console.warn(`JCP render: unknown page "${page}"`);
      return;
  }

  const request = new XMLHttpRequest();
  request.open('GET', templateUrl, false);
  request.send(null);

  if (request.status < 200 || request.status >= 300) {
    console.error(`JCP render: failed to load template ${templateUrl}`);
    return;
  }

  const doc = new DOMParser().parseFromString(request.responseText, 'text/html');
  const inlineScripts = [];

  doc.body.querySelectorAll('script').forEach((script) => {
    if (!script.src && script.textContent.trim()) {
      inlineScripts.push(script.textContent);
    }
    script.remove();
  });

  doc.body.querySelectorAll('link[rel="stylesheet"]').forEach((link) => {
    link.remove();
  });

  const wrapper = document.createElement('div');
  wrapper.innerHTML = doc.body.innerHTML;

  const rewriteAssetValue = (value) => {
    if (!value) return value;

    const sharedMatch = value.match(/^(?:\.\.\/)*shared\/assets\/(.+)$/);
    if (sharedMatch) {
      return `${assetBase}/shared/assets/${sharedMatch[1]}`;
    }

    if (value.startsWith('/shared/assets/')) {
      return `${assetBase}${value}`;
    }

    if (value.startsWith('/src/jcp-demo/')) {
      return `${baseUrl}/demo/`;
    }

    if (value.startsWith('/src/contractor-directory/')) {
      return `${baseUrl}/directory/`;
    }

    if (value.startsWith('/src/estimate-builder/')) {
      return `${baseUrl}/estimate/`;
    }

    if (value === 'index.html') {
      return `${baseUrl}/directory/`;
    }

    if (value.startsWith('profile.html')) {
      const query = value.includes('?') ? value.slice(value.indexOf('?')) : '';
      return `${baseUrl}/company/${query}`;
    }

    if (value.startsWith('../estimator-dashboard/')) {
      return `${baseUrl}/estimate/`;
    }

    return value;
  };

  wrapper.querySelectorAll('[src]').forEach((el) => {
    el.setAttribute('src', rewriteAssetValue(el.getAttribute('src')));
  });

  wrapper.querySelectorAll('[href]').forEach((el) => {
    el.setAttribute('href', rewriteAssetValue(el.getAttribute('href')));
  });

  wrapper.querySelectorAll('[poster]').forEach((el) => {
    el.setAttribute('poster', rewriteAssetValue(el.getAttribute('poster')));
  });

  root.innerHTML = '';
  while (wrapper.firstChild) {
    root.appendChild(wrapper.firstChild);
  }

  inlineScripts.forEach((code) => {
    const script = document.createElement('script');
    script.text = code;
    document.body.appendChild(script);
  });
})();
