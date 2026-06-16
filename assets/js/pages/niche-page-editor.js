(() => {
  const cfg = window.JCP_NICHE_EDITOR;
  if (!cfg || !cfg.postId || !cfg.restUrl) return;

  let flatContent = {};
  let pageDocument = { version: 1, blocks: [] };
  let registry = [];
  let editing = false;
  let dirty = false;
  let structureOpen = false;
  let dragIndex = null;

  const defaultProps = {
    hero: { h1: 'Page headline', subheadline: '', cta_primary: { label: 'Start free trial', url: '' }, cta_secondary: { label: 'See how it works', url: '#how-it-works' }, trust_line: '' },
    what_it_is: { headline: 'Section headline', subheadline: '' },
    how_it_works: { headline: 'How it works', subheadline: '', cta_label: 'See it in action', cta_url: '/demo', steps: [] },
    check_ins: { headline: 'Section headline', subheadline: '', features: [] },
    problem: { headline: 'Section headline', subheadline: '', pain_points: [] },
    benefits: { headline: 'Section headline', items: [] },
    differentiation: { headline: 'Section headline', body: '', bullets: [] },
    who_its_for: { headline: "Who it's for", audiences: [] },
    faq: { headline: 'Frequently asked questions', items: [] },
    final_cta: { headline: 'Ready to get started?', subheadline: '', cta_primary: { label: 'Start free trial', url: '' }, cta_secondary: { label: 'See how it works', url: '/demo' } },
    cta_band: { cta_primary: { label: 'Get started', url: '' }, band_key: 'cta_band_1' },
    breadcrumb: {},
    core_mechanic: [],
    commission: {},
    partners: {},
    share: {},
  };

  const setPath = (obj, path, value) => {
    const parts = path.split('.');
    let cur = obj;
    for (let i = 0; i < parts.length - 1; i++) {
      const key = parts[i];
      const next = parts[i + 1];
      if (/^\d+$/.test(next)) {
        if (!Array.isArray(cur[key])) cur[key] = [];
      } else if (cur[key] === undefined || cur[key] === null || typeof cur[key] !== 'object' || Array.isArray(cur[key])) {
        cur[key] = {};
      }
      cur = cur[key];
    }
    const last = parts[parts.length - 1];
    if (Array.isArray(cur)) {
      cur[parseInt(last, 10)] = value;
    } else {
      cur[last] = value;
    }
  };

  const bar = document.createElement('div');
  bar.className = 'jcp-niche-edit-bar';
  bar.innerHTML = `
    <strong class="jcp-niche-edit-bar-title">Page editor</strong>
    <button type="button" class="btn btn-secondary" id="jcpNicheStructureBtn">Page structure</button>
    <button type="button" class="btn btn-primary" id="jcpNicheToggleEdit">Click to edit page</button>
    <button type="button" class="btn btn-secondary" id="jcpNicheSave" disabled aria-label="Save changes">Save changes</button>
    <span id="jcpNicheStatus" class="jcp-niche-edit-status" aria-live="polite"></span>
    <a href="${cfg.adminUrl || '#'}" class="jcp-niche-edit-link">WP Admin</a>
  `;

  const structurePanel = document.createElement('aside');
  structurePanel.className = 'jcp-block-structure';
  structurePanel.hidden = true;
  structurePanel.innerHTML = `
    <div class="jcp-block-structure__header">
      <h2>Page structure</h2>
      <button type="button" class="jcp-block-structure__close" id="jcpStructureClose" aria-label="Close">×</button>
    </div>
    <p class="jcp-block-structure__hint">Drag to reorder. Add or remove sections, then save.</p>
    <ul class="jcp-block-structure__list" id="jcpBlockList"></ul>
    <button type="button" class="btn btn-secondary jcp-block-structure__add" id="jcpAddBlockBtn">+ Add block</button>
  `;

  const addModal = document.createElement('div');
  addModal.className = 'jcp-block-add-modal';
  addModal.hidden = true;
  addModal.innerHTML = `
    <div class="jcp-block-add-modal__dialog" role="dialog" aria-labelledby="jcpAddBlockTitle">
      <h3 id="jcpAddBlockTitle">Add block</h3>
      <ul class="jcp-block-add-modal__list" id="jcpAddBlockList"></ul>
      <button type="button" class="btn btn-secondary" id="jcpAddBlockCancel">Cancel</button>
    </div>
  `;

  const popover = document.createElement('div');
  popover.className = 'jcp-niche-link-popover';
  popover.hidden = true;
  popover.innerHTML = `
    <label>Button link URL</label>
    <input type="text" id="jcpNicheLinkUrl" placeholder="/demo or https://..." />
    <div class="jcp-niche-link-popover-actions">
      <button type="button" class="btn btn-primary" id="jcpNicheLinkApply">Apply</button>
      <button type="button" class="btn btn-secondary" id="jcpNicheLinkCancel">Cancel</button>
    </div>
  `;

  document.body.appendChild(bar);
  document.body.appendChild(structurePanel);
  document.body.appendChild(addModal);
  document.body.appendChild(popover);
  document.body.classList.add('jcp-niche-editing');

  const statusEl = bar.querySelector('#jcpNicheStatus');
  const saveBtn = bar.querySelector('#jcpNicheSave');
  const toggleBtn = bar.querySelector('#jcpNicheToggleEdit');
  const structureBtn = bar.querySelector('#jcpNicheStructureBtn');
  const blockListEl = structurePanel.querySelector('#jcpBlockList');
  const addBlockListEl = addModal.querySelector('#jcpAddBlockList');
  let activeLink = null;

  const blockLabel = (type) => {
    const found = registry.find((b) => b.type === type);
    return found ? found.label : type;
  };

  const markDirty = () => {
    dirty = true;
    saveBtn.disabled = false;
    saveBtn.classList.add('is-ready');
    statusEl.textContent = 'Unsaved changes';
  };

  const markClean = () => {
    dirty = false;
    saveBtn.disabled = true;
    saveBtn.classList.remove('is-ready');
  };

  const newBlockId = (type) => `b-${type}-${Math.random().toString(36).slice(2, 8)}`;

  const renderBlockList = () => {
    blockListEl.innerHTML = '';
    (pageDocument.blocks || []).forEach((block, index) => {
      const li = document.createElement('li');
      li.className = 'jcp-block-structure__item';
      li.draggable = true;
      li.dataset.index = String(index);
      li.innerHTML = `
        <span class="jcp-block-structure__handle" aria-hidden="true">⋮⋮</span>
        <span class="jcp-block-structure__label">${blockLabel(block.type)}</span>
        <button type="button" class="jcp-block-structure__remove" data-index="${index}" aria-label="Remove block">Remove</button>
      `;
      li.addEventListener('dragstart', (e) => {
        dragIndex = index;
        li.classList.add('is-dragging');
        e.dataTransfer.effectAllowed = 'move';
      });
      li.addEventListener('dragend', () => {
        dragIndex = null;
        li.classList.remove('is-dragging');
      });
      li.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
      });
      li.addEventListener('drop', (e) => {
        e.preventDefault();
        const from = dragIndex;
        const to = index;
        if (from === null || from === to) return;
        const blocks = pageDocument.blocks.slice();
        const [moved] = blocks.splice(from, 1);
        blocks.splice(to, 0, moved);
        pageDocument.blocks = blocks;
        dragIndex = null;
        renderBlockList();
        markDirty();
      });
      li.querySelector('.jcp-block-structure__remove').addEventListener('click', () => {
        if (!window.confirm(`Remove "${blockLabel(block.type)}" from this page?`)) return;
        pageDocument.blocks = pageDocument.blocks.filter((_, i) => i !== index);
        renderBlockList();
        markDirty();
      });
      blockListEl.appendChild(li);
    });
  };

  const renderAddBlockList = () => {
    addBlockListEl.innerHTML = '';
    registry.forEach((item) => {
      const li = document.createElement('li');
      li.innerHTML = `<button type="button" class="jcp-block-add-modal__option"><strong>${item.label}</strong><span>${item.description || ''}</span></button>`;
      li.querySelector('button').addEventListener('click', () => {
        const props = defaultProps[item.type] ? JSON.parse(JSON.stringify(defaultProps[item.type])) : {};
        pageDocument.blocks = pageDocument.blocks || [];
        pageDocument.blocks.push({ id: newBlockId(item.type), type: item.type, props });
        addModal.hidden = true;
        renderBlockList();
        markDirty();
      });
      addBlockListEl.appendChild(li);
    });
  };

  const openStructure = () => {
    structureOpen = true;
    structurePanel.hidden = false;
    document.body.classList.add('jcp-structure-open');
    renderBlockList();
  };

  const closeStructure = () => {
    structureOpen = false;
    structurePanel.hidden = true;
    document.body.classList.remove('jcp-structure-open');
  };

  const load = async () => {
    const res = await fetch(cfg.restUrl, {
      credentials: 'same-origin',
      headers: { 'X-WP-Nonce': cfg.nonce },
    });
    const data = await res.json();
    flatContent = data.content || {};
    pageDocument = data.blocks && data.blocks.blocks ? data.blocks : { version: 1, blocks: [] };
    if (!pageDocument.blocks) pageDocument.blocks = [];
    registry = data.registry || [];
  };

  const collectFromDom = () => {
    document.querySelectorAll('[data-jcp-path]').forEach((el) => {
      const path = el.getAttribute('data-jcp-path');
      if (!path) return;
      setPath(flatContent, path, (el.textContent || '').trim());
    });
    document.querySelectorAll('[data-jcp-href-path]').forEach((el) => {
      const path = el.getAttribute('data-jcp-href-path');
      if (!path) return;
      setPath(flatContent, path, el.getAttribute('href') || '');
    });
  };

  const enableEditing = () => {
    editing = true;
    document.body.classList.add('jcp-inline-editing');
    toggleBtn.textContent = 'Editing — click text to change';
    toggleBtn.classList.add('is-active');
    if (!dirty) statusEl.textContent = 'Click highlighted text or buttons to edit';

    document.querySelectorAll('[data-jcp-path]').forEach((el) => {
      el.setAttribute('contenteditable', 'true');
      el.setAttribute('spellcheck', 'true');
      el.addEventListener('input', markDirty);
    });

    document.querySelectorAll('[data-jcp-href-path]').forEach((el) => {
      el.addEventListener('click', (e) => {
        if (!editing) return;
        e.preventDefault();
        e.stopPropagation();
        activeLink = el;
        popover.querySelector('#jcpNicheLinkUrl').value = el.getAttribute('href') || '';
        popover.hidden = false;
        const rect = el.getBoundingClientRect();
        popover.style.top = `${Math.min(window.innerHeight - 120, rect.bottom + 8)}px`;
        popover.style.left = `${Math.max(8, Math.min(window.innerWidth - 320, rect.left))}px`;
      });
    });
  };

  const disableEditing = () => {
    editing = false;
    document.body.classList.remove('jcp-inline-editing');
    toggleBtn.textContent = 'Click to edit page';
    toggleBtn.classList.remove('is-active');
    popover.hidden = true;
    document.querySelectorAll('[data-jcp-path]').forEach((el) => {
      el.removeAttribute('contenteditable');
      el.removeAttribute('spellcheck');
    });
  };

  structureBtn.addEventListener('click', () => {
    if (structureOpen) closeStructure();
    else openStructure();
  });

  structurePanel.querySelector('#jcpStructureClose').addEventListener('click', closeStructure);
  structurePanel.querySelector('#jcpAddBlockBtn').addEventListener('click', () => {
    renderAddBlockList();
    addModal.hidden = false;
  });
  addModal.querySelector('#jcpAddBlockCancel').addEventListener('click', () => {
    addModal.hidden = true;
  });
  addModal.addEventListener('click', (e) => {
    if (e.target === addModal) addModal.hidden = true;
  });

  toggleBtn.addEventListener('click', () => {
    if (editing) {
      if (dirty && !window.confirm('You have unsaved changes. Stop editing anyway?')) return;
      disableEditing();
    } else {
      enableEditing();
    }
  });

  popover.querySelector('#jcpNicheLinkApply').addEventListener('click', () => {
    if (!activeLink) return;
    activeLink.setAttribute('href', popover.querySelector('#jcpNicheLinkUrl').value.trim());
    popover.hidden = true;
    markDirty();
  });

  popover.querySelector('#jcpNicheLinkCancel').addEventListener('click', () => {
    popover.hidden = true;
    activeLink = null;
  });

  saveBtn.addEventListener('click', async () => {
    if (saveBtn.disabled) return;
    collectFromDom();
    statusEl.textContent = 'Saving…';
    saveBtn.disabled = true;
    const res = await fetch(cfg.restUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': cfg.nonce },
      body: JSON.stringify({ blocks: pageDocument, content: flatContent }),
    });
    if (res.ok) {
      markClean();
      statusEl.textContent = 'Saved';
      window.location.reload();
    } else {
      statusEl.textContent = 'Save failed — try again';
      saveBtn.disabled = false;
      saveBtn.classList.add('is-ready');
    }
  });

  document.addEventListener('keydown', (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 's' && dirty) {
      e.preventDefault();
      saveBtn.click();
    }
    if (e.key === 'Escape' && structureOpen) closeStructure();
  });

  window.addEventListener('beforeunload', (e) => {
    if (dirty) {
      e.preventDefault();
      e.returnValue = '';
    }
  });

  load().then(() => {
    if (new URLSearchParams(window.location.search).get('jcp_edit') === '1') {
      enableEditing();
    }
    if (new URLSearchParams(window.location.search).get('jcp_structure') === '1') {
      openStructure();
    }
  });
})();
