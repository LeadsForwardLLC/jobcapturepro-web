(() => {
  const CHECK_SVG = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';

  const cleanStepLineText = (text) => {
    let value = (text || '').trim();
    for (let i = 0; i < 3; i += 1) {
      value = value.replace(/(?:nttt)+x*\s*$/giu, '');
      value = value.replace(/[\s\u00D7]+$/gu, '');
      value = value.replace(/x\s*$/giu, '');
      value = value.trim();
    }
    return value;
  };

  const ARRAY_DEFAULTS = {
    'conversion.points': () => 'New point',
    'differentiation.bullets': () => 'New point',
    'what_it_is.team_already': () => 'New item',
    'what_it_is.turns_into': () => 'New item',
    'check_ins.job_types': () => 'New tag',
    'faq.items': () => ({ q: 'New question', a: 'Answer text.' }),
    'how_it_works.steps': () => ({ title: 'New step', lines: ['Step description'] }),
    'benefits.items': () => ({
      icon: 'badge-check',
      title: 'New benefit',
      body: 'Description',
      stat_value: 'Stat',
      stat_label: 'label',
    }),
    'check_ins.features': () => ({ title: 'Feature', body: 'Description' }),
    'problem.pain_points': () => ({ title: 'Pain point', body: 'Description' }),
    'who_its_for.audiences': () => ({
      title: 'Audience',
      body: 'Description',
      badge: 'Badge',
      stat_number: '100%',
      stat_label: 'Label',
    }),
  };

  const OPTIONAL_DEFAULTS = {
    'conversion.cta_primary': () => ({ label: 'Button label', url: '/demo' }),
    'benefits.cta_primary': () => ({ label: 'See it in the demo', url: '/demo' }),
    'benefits.cta_secondary': () => ({ label: 'Learn more', url: '/pricing' }),
    'hero.cta_primary': () => ({ label: 'View the live demo', url: '/demo' }),
    'hero.cta_secondary': () => ({ label: 'Learn how it works', url: '#how-it-works' }),
    'final_cta.cta_primary': () => ({ label: 'Get started', url: '/demo' }),
  };

  const OPTIONAL_TEMPLATES = {
    cta: (path, data) => {
      const cls = path.includes('conversion') ? 'btn btn-primary conversion-cta-btn'
        : path.includes('benefits') ? 'btn btn-primary'
          : path.includes('final_cta') ? 'btn btn-primary rankings-cta-btn'
            : 'btn btn-primary';
      return `<a href="${esc(data.url)}" class="${cls}" data-jcp-path="${path}.label" data-jcp-href-path="${path}.url">${esc(data.label)}</a>`;
    },
    link: (path, data) => `<a href="${esc(data.url)}" class="benefits-cta-link" data-jcp-path="${path}.label" data-jcp-href-path="${path}.url">${esc(data.label)}<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>`,
  };

  let api = null;

  const esc = (s) => String(s ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const getArray = (path) => {
    const val = api.getPath(api.flatContent, path);
    return Array.isArray(val) ? val : [];
  };

  const setArray = (path, arr) => {
    api.setPath(api.flatContent, path, arr);
  };

  const iconUrl = (name) => {
    const base = window.JCP_ASSET_BASE || '';
    return `${base}/shared/assets/icons/lucide/${name}.svg`;
  };

  const buildFactorCard = (basePath, index, data) => {
    const stat = data.stat_value
      ? `<div class="factor-stat">
          <span class="stat-value" data-jcp-path="${basePath}.${index}.stat_value">${esc(data.stat_value)}</span>
          <span class="stat-label" data-jcp-path="${basePath}.${index}.stat_label">${esc(data.stat_label || '')}</span>
        </div>`
      : '';
    return `
      <div class="ranking-factor-card" data-jcp-array-item="${index}">
        <div class="factor-icon-wrapper">
          <img src="${iconUrl(data.icon || 'badge-check')}" class="factor-icon" alt="" width="32" height="32" />
        </div>
        <h3 class="factor-title" data-jcp-path="${basePath}.${index}.title">${esc(data.title || '')}</h3>
        <div class="factor-description"><p data-jcp-path="${basePath}.${index}.body">${esc(data.body || '')}</p></div>
        ${stat}
      </div>`;
  };

  const buildTimelineStep = (basePath, index, data) => {
    const lines = Array.isArray(data.lines) ? data.lines : [data.body || data.description || 'Step description'];
    const linesHtml = lines.map((line, li) =>
      `<li data-jcp-array-item="${li}">
        <span class="jcp-step-checklist__icon" aria-hidden="true">${CHECK_SVG}</span>
        <span class="jcp-step-checklist__text" data-jcp-path="${basePath}.${index}.lines.${li}">${esc(cleanStepLineText(line))}</span>
      </li>`
    ).join('');
    return `
      <div class="timeline-step" data-jcp-array-item="${index}">
        <div class="step-number">${index + 1}</div>
        <div class="step-content">
          <h4 class="step-title" data-jcp-path="${basePath}.${index}.title">${esc(data.title || '')}</h4>
          <ul class="jcp-step-checklist jcp-niche-checklist" data-jcp-array="${basePath}.${index}.lines">${linesHtml}</ul>
        </div>
      </div>`;
  };

  const buildConversionPoint = (basePath, index, text) => `
    <div class="conversion-point" data-jcp-array-item="${index}">
      <div class="conversion-point-icon">${CHECK_SVG}</div>
      <div class="conversion-point-text">
        <strong data-jcp-path="${basePath}.${index}">${esc(text)}</strong>
      </div>
    </div>`;

  const buildChecklistItem = (basePath, index, text) => `<li data-jcp-array-item="${index}"><span class="jcp-step-checklist__icon" aria-hidden="true">${CHECK_SVG}</span><span class="jcp-step-checklist__text" data-jcp-path="${basePath}.${index}">${esc(cleanStepLineText(text))}</span></li>`;

  const buildListItem = (basePath, index, text) => `<li data-jcp-array-item="${index}"><span class="jcp-checklist-item__text" data-jcp-path="${basePath}.${index}">${esc(cleanStepLineText(text))}</span></li>`;

  const buildTagItem = (basePath, index, text) => `<li data-jcp-array-item="${index}"><span class="jcp-checklist-item__text" data-jcp-path="${basePath}.${index}">${esc(cleanStepLineText(text))}</span></li>`;

  const buildFaqItem = (index, item) => {
    const qPath = `faq.items.${index}.q`;
    const aPath = `faq.items.${index}.a`;
    return `
      <details class="faq-item" id="faq-${index}" data-jcp-array-item="${index}">
        <summary data-jcp-path="${qPath}">${esc(item.q || '')}</summary>
        <p data-jcp-path="${aPath}">${esc(item.a || '')}</p>
      </details>`;
  };

  const buildGuaranteeCard = (basePath, index, data) => {
    const path = `${basePath}.${index}`;
    const imageBlock = data.image_url
      ? `<img src="${esc(data.image_url)}" alt="${esc(data.image_alt || '')}" class="guarantee-image jcp-editable-media-image" loading="lazy" data-jcp-media-url-path="${path}.image_url" data-jcp-media-alt-path="${path}.image_alt" data-jcp-media-types="image" />`
      : `<div class="guarantee-image guarantee-image--empty" data-jcp-media-url-path="${path}.image_url" data-jcp-media-alt-path="${path}.image_alt" data-jcp-media-types="image"></div>`;
    const badge = data.badge
      ? `<div class="guarantee-badge" data-jcp-path="${path}.badge">${esc(data.badge)}</div>`
      : '';
    const stat = data.stat_number
      ? `<div class="guarantee-stat"><span class="stat-number" data-jcp-path="${path}.stat_number">${esc(data.stat_number)}</span><span class="stat-label" data-jcp-path="${path}.stat_label">${esc(data.stat_label || '')}</span></div>`
      : '';
    const faqTarget = data.faq_target ? ` data-faq-target="${esc(data.faq_target)}"` : '';
    return `
      <a href="#faq" class="guarantee-item" data-jcp-array-item="${index}"${faqTarget}>
        <div class="guarantee-image-wrapper jcp-editable-media-wrap">${imageBlock}${badge}</div>
        <div class="guarantee-content">
          <strong data-jcp-path="${path}.title">${esc(data.title || '')}</strong>
          <p data-jcp-path="${path}.body">${esc(data.body || '')}</p>
          ${stat}
        </div>
      </a>`;
  };

  const arrayDefaultFactory = (basePath) => {
    if (ARRAY_DEFAULTS[basePath]) return ARRAY_DEFAULTS[basePath];
    if (/\.lines$/.test(basePath)) return () => 'New point';
    return () => 'New item';
  };

  const buildItemHtml = (basePath, index, data, container) => {
    if (typeof data === 'string') {
      if (basePath.endsWith('.lines')) return buildChecklistItem(basePath, index, data);
      if (basePath.endsWith('.points') || basePath.endsWith('.bullets')) return buildConversionPoint(basePath, index, data);
      if (basePath.includes('team_already') || basePath.includes('turns_into')) return buildListItem(basePath, index, data);
      if (basePath.endsWith('.job_types')) return buildTagItem(basePath, index, data);
      return '';
    }
    if (basePath === 'faq.items') return buildFaqItem(index, data);
    if (basePath === 'how_it_works.steps') return buildTimelineStep(basePath, index, data);
    if (basePath === 'who_its_for.audiences' && container.classList.contains('guarantees-grid')) {
      return buildGuaranteeCard(basePath, index, data);
    }
    if (basePath.endsWith('.items') || basePath.endsWith('.features') || basePath.endsWith('.pain_points') || basePath.endsWith('.audiences')) {
      return buildFactorCard(basePath, index, data);
    }
    return '';
  };

  const formatStepNumber = (position) => {
    const numeric = api?.getPath?.(api.flatContent, 'how_it_works.numeric_steps');
    const n = position + 1;
    return numeric ? String(n) : String(n).padStart(2, '0');
  };

  const updateTimelineStepNumbers = (container) => {
    const basePath = 'how_it_works.steps';
    const containers = container
      ? [container]
      : [...document.querySelectorAll(`.timeline-steps[data-jcp-array="${basePath}"]`)];
    containers.forEach((stepsContainer) => {
      const steps = stepsContainer.querySelectorAll(':scope > .timeline-step');
      steps.forEach((step, index) => {
        step.dataset.jcpArrayItem = String(index);
        const numEl = step.querySelector(':scope > .step-number');
        if (numEl) numEl.textContent = formatStepNumber(index);
        step.querySelectorAll('[data-jcp-path]').forEach((el) => {
          const path = el.getAttribute('data-jcp-path');
          if (!path || !path.startsWith(`${basePath}.`)) return;
          const rest = path.slice(basePath.length + 1).split('.');
          rest[0] = String(index);
          el.setAttribute('data-jcp-path', `${basePath}.${rest.join('.')}`);
        });
      });
    });
  };

  const rebuildArrayContainer = (container) => {
    const basePath = container.dataset.jcpArray;
    if (!basePath || !api) return;
    const arr = getArray(basePath);
    container.querySelectorAll(':scope > [data-jcp-array-item], :scope > .jcp-collection-add').forEach((el) => el.remove());
    const temp = document.createElement('div');
    arr.forEach((item, index) => {
      const html = buildItemHtml(basePath, index, item, container);
      if (!html) return;
      temp.innerHTML = html.trim();
      if (temp.firstElementChild) container.appendChild(temp.firstElementChild);
    });
    if (basePath === 'how_it_works.steps') updateTimelineStepNumbers(container);
  };

  const syncOptionalSlotsFromContent = () => {
    if (!api) return;
    document.querySelectorAll('[data-jcp-optional]').forEach((slot) => {
      const path = slot.dataset.jcpOptional;
      if (!path) return;
      const kind = slot.dataset.jcpOptionalKind || 'cta';
      const data = api.getPath(api.flatContent, path);
      const hasLabel = data && typeof data === 'object' && String(data.label || '').trim() !== '';
      if (!hasLabel) {
        slot.innerHTML = '';
        slot.classList.add('is-empty');
      } else {
        const tpl = OPTIONAL_TEMPLATES[kind] || OPTIONAL_TEMPLATES.cta;
        slot.innerHTML = tpl(path, data);
        slot.classList.remove('is-empty');
      }
    });
  };

  const syncCollectionsFromContent = () => {
    if (!api) return;
    document.querySelectorAll('[data-jcp-array]').forEach(rebuildArrayContainer);
    syncOptionalSlotsFromContent();
    updateTimelineStepNumbers();
  };

  const addArrayItem = (container, basePath) => {
    if (!api || typeof api.collectFromDom !== 'function') return;
    api.collectFromDom();
    const arr = getArray(basePath);
    const factory = arrayDefaultFactory(basePath);
    const data = factory(arr.length);
    arr.push(data);
    setArray(basePath, arr);
    rebuildArrayContainer(container);
    refreshCollections();
    if (typeof window.JCP_REFRESH_INLINE_EDITABLE === 'function') {
      window.JCP_REFRESH_INLINE_EDITABLE();
    }
    if (typeof window.JCP_REFRESH_PAGE_MEDIA_UI === 'function') {
      window.JCP_REFRESH_PAGE_MEDIA_UI();
    }
    api.recordChange();
  };

  const removeArrayItem = (container, basePath, index) => {
    if (!api || typeof api.collectFromDom !== 'function') return;
    api.collectFromDom();
    const arr = getArray(basePath);
    if (index < 0 || index >= arr.length) return;
    arr.splice(index, 1);
    setArray(basePath, arr);
    rebuildArrayContainer(container);
    refreshCollections();
    if (typeof window.JCP_REFRESH_INLINE_EDITABLE === 'function') {
      window.JCP_REFRESH_INLINE_EDITABLE();
    }
    if (typeof window.JCP_REFRESH_PAGE_MEDIA_UI === 'function') {
      window.JCP_REFRESH_PAGE_MEDIA_UI();
    }
    api.recordChange();
  };

  const isOptionalEmpty = (path) => {
    const val = api.getPath(api.flatContent, path);
    if (!val || typeof val !== 'object') return true;
    return !String(val.label || '').trim();
  };

  const restoreOptional = (slot) => {
    const path = slot.dataset.jcpOptional;
    const kind = slot.dataset.jcpOptionalKind || 'cta';
    const factory = OPTIONAL_DEFAULTS[path] || (() => ({ label: 'Button', url: '#' }));
    const data = factory();
    api.setPath(api.flatContent, path, data);
    const tpl = OPTIONAL_TEMPLATES[kind] || OPTIONAL_TEMPLATES.cta;
    slot.innerHTML = tpl(path, data);
    slot.classList.remove('is-empty');
    refreshCollections();
    if (typeof window.JCP_REFRESH_INLINE_EDITABLE === 'function') {
      window.JCP_REFRESH_INLINE_EDITABLE();
    }
    api.recordChange();
  };

  const removeOptional = (slot) => {
    const path = slot.dataset.jcpOptional;
    if (!api || typeof api.collectFromDom !== 'function') return;
    api.collectFromDom();
    api.setPath(api.flatContent, path, { label: '', url: '' });
    slot.innerHTML = '';
    slot.classList.add('is-empty');
    ensureOptionalPlaceholder(slot);
    refreshCollections();
    api.recordChange();
  };

  const ensureOptionalPlaceholder = (slot) => {
    if (!slot.classList.contains('is-empty')) return;
    if (!slot.querySelector('.jcp-optional-restore')) {
      const ph = document.createElement('button');
      ph.type = 'button';
      ph.className = 'jcp-optional-restore';
      ph.textContent = slot.dataset.jcpOptionalLabel || 'Add button';
      slot.appendChild(ph);
    }
  };

  const injectRemoveButton = (item) => {
    if (item.querySelector('.jcp-collection-remove')) return;
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'jcp-collection-remove';
    btn.setAttribute('aria-label', 'Remove item');
    btn.title = 'Remove';
    btn.textContent = '×';
    item.classList.add('jcp-collection-item');
    item.appendChild(btn);
  };

  const injectOptionalControls = (slot) => {
    const content = slot.querySelector('a, .btn');
    if (content && !slot.querySelector('.jcp-collection-remove')) {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'jcp-collection-remove jcp-collection-remove--optional';
      btn.setAttribute('aria-label', 'Remove');
      btn.title = 'Remove';
      btn.textContent = '×';
      slot.classList.add('jcp-optional-slot');
      slot.appendChild(btn);
    }
    if (isOptionalEmpty(slot.dataset.jcpOptional)) {
      slot.classList.add('is-empty');
      ensureOptionalPlaceholder(slot);
    } else {
      slot.classList.remove('is-empty');
      slot.querySelector('.jcp-optional-restore')?.remove();
    }
  };

  const injectAddButton = (container) => {
    let btn = container.querySelector(':scope > .jcp-collection-add');
    if (btn) btn.remove();
    btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'jcp-collection-add';
    const label = container.classList.contains('faq-grid') ? '+ Add question'
      : container.classList.contains('timeline-steps') ? '+ Add step'
        : container.classList.contains('jcp-step-checklist') ? '+ Add point'
          : container.classList.contains('ranking-factors-grid') ? '+ Add card'
            : '+ Add item';
    btn.textContent = label;
    container.appendChild(btn);
  };

  const teardownCollections = () => {
    document.querySelectorAll('.jcp-collection-remove, .jcp-collection-add, .jcp-optional-restore').forEach((el) => el.remove());
    document.querySelectorAll('.jcp-collection-item, .jcp-optional-slot').forEach((el) => {
      el.classList.remove('jcp-collection-item', 'jcp-optional-slot', 'is-empty');
    });
  };

  const refreshCollections = () => {
    if (!api || !api.editing()) return;
    teardownCollections();

    document.querySelectorAll('[data-jcp-array]').forEach((container) => {
      container.querySelectorAll(':scope > [data-jcp-array-item]').forEach(injectRemoveButton);
      injectAddButton(container);
    });

    document.querySelectorAll('[data-jcp-optional]').forEach(injectOptionalControls);
    updateTimelineStepNumbers();
  };

  const init = (editorApi) => {
    api = editorApi;
    window.JCP_REFRESH_COLLECTIONS = refreshCollections;
    window.JCP_TEARDOWN_COLLECTIONS = teardownCollections;
    window.JCP_SYNC_COLLECTIONS_FROM_CONTENT = syncCollectionsFromContent;

    if (!document.body.dataset.jcpCollectionBound) {
      document.body.dataset.jcpCollectionBound = '1';
      document.addEventListener('click', (e) => {
        if (!api || !api.editing()) return;

        const addBtn = e.target.closest('.jcp-collection-add');
        if (addBtn) {
          e.preventDefault();
          e.stopPropagation();
          const container = addBtn.closest('[data-jcp-array]');
          if (container?.dataset.jcpArray) addArrayItem(container, container.dataset.jcpArray);
          return;
        }

        const removeBtn = e.target.closest('.jcp-collection-remove');
        if (removeBtn) {
          e.preventDefault();
          e.stopPropagation();
          if (removeBtn.classList.contains('jcp-collection-remove--optional')) {
            const slot = removeBtn.closest('[data-jcp-optional]');
            if (slot) removeOptional(slot);
            return;
          }
          const item = removeBtn.closest('[data-jcp-array-item]');
          const container = item?.closest('[data-jcp-array]');
          if (item && container?.dataset.jcpArray) {
            const index = parseInt(item.dataset.jcpArrayItem, 10);
            if (!Number.isNaN(index)) removeArrayItem(container, container.dataset.jcpArray, index);
          }
          return;
        }

        const restoreBtn = e.target.closest('.jcp-optional-restore');
        if (restoreBtn) {
          e.preventDefault();
          e.stopPropagation();
          const slot = restoreBtn.closest('[data-jcp-optional]');
          if (slot) restoreOptional(slot);
        }
      });
    }
  };

  if (window.__JCP_EDITOR_API__) init(window.__JCP_EDITOR_API__);
  window.JCP_INIT_COLLECTION_EDITOR = init;
})();
