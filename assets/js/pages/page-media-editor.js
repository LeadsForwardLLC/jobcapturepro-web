/**
 * Front-end media picker, per-instance alt text, type toggle, column drag-swap, and optional media links.
 */
(() => {
  const MEDIA_TYPES = [
    { value: 'image', label: 'Image' },
    { value: 'video', label: 'Video' },
    { value: 'phone_mockup', label: 'Phone mockup' },
  ];

  let api = null;
  let popover = null;
  let activeTarget = null;
  let mediaFrame = null;
  let dragSourceCol = null;

  const legacyKey = (type) => {
    const found = api.registry.find((b) => b.type === type);
    return found?.legacy_key || type;
  };

  const syncFlatProp = (path, value) => {
    api.setPath(api.flatContent, path, value);
    (api.pageDocument.blocks || []).forEach((block) => {
      const key = legacyKey(block.type);
      if (!key || (path !== key && !path.startsWith(`${key}.`))) return;
      const rel = path.slice(key.length + 1);
      block.props = block.props || {};
      if (rel) api.setPath(block.props, rel, value);
    });
  };

  const getMediaPaths = (el) => {
    const slot = el.closest('.jcp-media-slot');
    const basePath = slot?.dataset.jcpMediaPath || '';
    const urlPath = el.dataset.jcpMediaUrlPath
      || slot?.dataset.jcpMediaUrlPath
      || (basePath ? `${basePath}.media_url` : null);
    const altPath = el.dataset.jcpMediaAltPath
      || slot?.dataset.jcpMediaAltPath
      || (basePath ? `${basePath}.media_alt` : null);
    const typePath = basePath
      ? `${basePath}.media_type`
      : (el.dataset.jcpMediaUrlPath ? el.dataset.jcpMediaUrlPath.replace(/\.(media_url|image_url)$/, '.media_type') : null);
    const linkPath = basePath ? `${basePath}.media_link_url` : null;
    return { slot, urlPath, altPath, typePath, linkPath, basePath };
  };

  const resolveMediaClickTarget = (el) => {
    if (!el) return null;
    if (el.matches('.jcp-editable-media-image')) return el;
    if (el.matches('.jcp-media-slot')) {
      return el.querySelector('.jcp-editable-media-image')
        || el.querySelector('.jcp-media-variant:not([hidden])')
        || el;
    }
    const img = el.querySelector?.('.jcp-editable-media-image');
    if (img) return img;
    if (el.matches('.guarantee-image--empty, .conversion-image-wrapper, .jcp-split-col--media, .jcp-editable-media-wrap')) {
      return el.querySelector('.jcp-editable-media-image, .jcp-media-slot') || el;
    }
    return el.closest('.jcp-editable-media-image, .jcp-media-slot') || null;
  };

  const allowedTypes = (el) => {
    const raw = el.dataset.jcpMediaTypes || el.closest('.jcp-media-slot')?.dataset.jcpMediaTypes;
    if (!raw) return MEDIA_TYPES;
    const allowed = raw.split(',').map((s) => s.trim()).filter(Boolean);
    return MEDIA_TYPES.filter((t) => allowed.includes(t.value));
  };

  const currentMediaType = (paths) => {
    if (paths.typePath) {
      const stored = api.getPath(api.flatContent, paths.typePath);
      if (stored) return stored;
    }
    if (paths.slot?.dataset.jcpMediaType) return paths.slot.dataset.jcpMediaType;
    return 'image';
  };

  const updateVariantVisibility = (slot, type) => {
    if (!slot) return;
    slot.querySelectorAll('.jcp-media-variant').forEach((node) => {
      const match = node.classList.contains(`jcp-media-variant--${type}`);
      if (match) node.removeAttribute('hidden');
      else node.setAttribute('hidden', '');
    });
    slot.dataset.jcpMediaType = type;
  };

  const updateMediaDom = (urlPath, url, altPath, alt) => {
    if (!urlPath) return;
    document.querySelectorAll(`[data-jcp-media-url-path="${urlPath}"]`).forEach((node) => {
      if (node.tagName === 'IMG') {
        if (url) node.src = url;
        if (altPath && alt !== undefined) node.alt = alt;
      } else if (node.classList.contains('guarantee-image--empty') && url) {
        const img = document.createElement('img');
        img.src = url;
        img.alt = alt || '';
        img.className = 'guarantee-image jcp-editable-media-image';
        img.loading = 'lazy';
        img.dataset.jcpMediaUrlPath = urlPath;
        if (altPath) img.dataset.jcpMediaAltPath = altPath;
        img.dataset.jcpMediaTypes = 'image';
        node.replaceWith(img);
      }
    });
    if (urlPath && url) {
      const base = urlPath.replace(/\.(media_url|image_url)$/, '');
      const slot = document.querySelector(`.jcp-media-slot[data-jcp-media-path="${base}"]`);
      const videoWrap = slot?.querySelector('.jcp-media-variant--video');
      if (videoWrap) {
        const iframe = videoWrap.querySelector('iframe');
        const video = videoWrap.querySelector('video');
        if (video) video.src = url;
        if (iframe) {
          const yt = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
          const vm = url.match(/vimeo\.com\/(\d+)/);
          if (yt) iframe.src = `https://www.youtube.com/embed/${yt[1]}`;
          else if (vm) iframe.src = `https://player.vimeo.com/video/${vm[1]}`;
        }
      }
    }
  };

  const ensurePopover = () => {
    if (popover) return popover;
    popover = document.createElement('div');
    popover.className = 'jcp-media-popover';
    popover.hidden = true;
    popover.setAttribute('hidden', '');
    popover.innerHTML = `
      <div class="jcp-media-popover__header">
        <strong>Edit media</strong>
        <button type="button" class="jcp-media-popover__close" aria-label="Close">×</button>
      </div>
      <label class="jcp-media-popover__field">
        <span>Media type</span>
        <select id="jcpMediaTypeSelect"></select>
      </label>
      <label class="jcp-media-popover__field">
        <span>ALT text <small>(this page only)</small></span>
        <input type="text" id="jcpMediaAltInput" placeholder="Describe this image for accessibility and SEO">
      </label>
      <label class="jcp-media-popover__field jcp-media-popover__field--video" hidden>
        <span>Video URL</span>
        <input type="url" id="jcpMediaVideoUrlInput" placeholder="YouTube, Vimeo, or MP4 URL">
      </label>
      <label class="jcp-media-popover__field jcp-media-popover__field--link">
        <span>Link URL <small>(optional)</small></span>
        <input type="url" id="jcpMediaLinkInput" placeholder="Leave empty for no link">
      </label>
      <div class="jcp-media-popover__actions">
        <button type="button" class="btn btn-secondary" id="jcpMediaReplaceBtn">Choose from library</button>
        <button type="button" class="btn btn-primary" id="jcpMediaApplyBtn">Apply</button>
      </div>
    `;
    document.body.appendChild(popover);

    popover.querySelector('.jcp-media-popover__close').addEventListener('click', closePopover);
    popover.querySelector('#jcpMediaApplyBtn').addEventListener('click', applyPopover);
    popover.querySelector('#jcpMediaReplaceBtn').addEventListener('click', openLibrary);
    popover.querySelector('#jcpMediaTypeSelect').addEventListener('change', onTypeSelectChange);

    document.addEventListener('click', (e) => {
      if (!popover || popover.hidden) return;
      if (popover.contains(e.target)) return;
      if (e.target.closest('.jcp-media-hit, .jcp-editable-media-image, .jcp-media-slot, .guarantee-image--empty, .jcp-editable-media-wrap, .conversion-image-wrapper, .jcp-split-col--media')) return;
      closePopover();
    });

    return popover;
  };

  const onTypeSelectChange = () => {
    const type = popover.querySelector('#jcpMediaTypeSelect').value;
    const videoField = popover.querySelector('.jcp-media-popover__field--video');
    const replaceBtn = popover.querySelector('#jcpMediaReplaceBtn');
    const showVideo = type === 'video';
    videoField.hidden = !showVideo;
    if (showVideo) videoField.removeAttribute('hidden');
    else videoField.setAttribute('hidden', '');
    replaceBtn.hidden = type === 'phone_mockup';
    if (type === 'phone_mockup') replaceBtn.setAttribute('hidden', '');
    else replaceBtn.removeAttribute('hidden');
  };

  const openPopover = (el) => {
    if (!api?.editing()) return;
    const target = resolveMediaClickTarget(el);
    if (!target) return;
    activeTarget = target;
    const paths = getMediaPaths(target);
    if (!paths.urlPath && !paths.altPath) return;

    ensurePopover();
    const types = allowedTypes(target);
    const select = popover.querySelector('#jcpMediaTypeSelect');
    select.innerHTML = types.map((t) => `<option value="${t.value}">${t.label}</option>`).join('');

    const type = currentMediaType(paths);
    select.value = types.some((t) => t.value === type) ? type : (types[0]?.value || 'image');

    popover.querySelector('#jcpMediaAltInput').value = paths.altPath ? (api.getPath(api.flatContent, paths.altPath) || '') : '';
    popover.querySelector('#jcpMediaVideoUrlInput').value = paths.urlPath ? (api.getPath(api.flatContent, paths.urlPath) || '') : '';
    popover.querySelector('#jcpMediaLinkInput').value = paths.linkPath ? (api.getPath(api.flatContent, paths.linkPath) || '') : '';

    const linkField = popover.querySelector('.jcp-media-popover__field--link');
    linkField.hidden = !paths.linkPath;
    if (!paths.linkPath) linkField.setAttribute('hidden', '');
    else linkField.removeAttribute('hidden');

    onTypeSelectChange();

    const rect = (target.getBoundingClientRect ? target : el).getBoundingClientRect();
    popover.hidden = false;
    popover.removeAttribute('hidden');
    popover.style.top = `${Math.min(window.innerHeight - 320, rect.bottom + 8)}px`;
    popover.style.left = `${Math.max(8, Math.min(window.innerWidth - 340, rect.left))}px`;
  };

  const closePopover = () => {
    if (!popover) return;
    popover.hidden = true;
    popover.setAttribute('hidden', '');
    activeTarget = null;
  };

  const applyPopover = () => {
    if (!activeTarget) return;
    const paths = getMediaPaths(activeTarget);
    const type = popover.querySelector('#jcpMediaTypeSelect').value;
    const alt = popover.querySelector('#jcpMediaAltInput').value.trim();
    let url = paths.urlPath ? (api.getPath(api.flatContent, paths.urlPath) || '') : '';

    if (type === 'video') {
      url = popover.querySelector('#jcpMediaVideoUrlInput').value.trim();
      if (paths.urlPath) syncFlatProp(paths.urlPath, url);
    }

    if (paths.altPath) syncFlatProp(paths.altPath, alt);
    if (paths.typePath) syncFlatProp(paths.typePath, type);
    if (paths.linkPath) syncFlatProp(paths.linkPath, popover.querySelector('#jcpMediaLinkInput').value.trim());

    if (paths.urlPath && type === 'image' && url) {
      syncFlatProp(paths.urlPath, url);
    }

    updateVariantVisibility(paths.slot, type);
    updateMediaDom(paths.urlPath, url, paths.altPath, alt);
    api.recordChange();
    closePopover();
  };

  const openLibrary = () => {
    if (!window.wp?.media) {
      window.alert('Media library is not available. Try refreshing the page.');
      return;
    }
    if (!mediaFrame) {
      mediaFrame = window.wp.media({
        title: api.strings?.mediaTitle || 'Choose or upload media',
        button: { text: api.strings?.mediaButton || 'Use this media' },
        multiple: false,
      });
      mediaFrame.on('select', () => {
        const attachment = mediaFrame.state().get('selection').first().toJSON();
        if (!activeTarget) return;
        const paths = getMediaPaths(activeTarget);
        const url = attachment.url || '';
        const mime = attachment.mime || '';
        const type = mime.startsWith('video/') ? 'video' : 'image';
        const libAlt = attachment.alt || attachment.title || '';

        if (paths.urlPath) syncFlatProp(paths.urlPath, url);
        if (paths.typePath) syncFlatProp(paths.typePath, type);
        if (paths.altPath && !popover.querySelector('#jcpMediaAltInput').value.trim() && libAlt) {
          popover.querySelector('#jcpMediaAltInput').value = libAlt;
        }

        popover.querySelector('#jcpMediaTypeSelect').value = type;
        if (type === 'video') popover.querySelector('#jcpMediaVideoUrlInput').value = url;
        onTypeSelectChange();
        updateVariantVisibility(paths.slot, type);
        updateMediaDom(paths.urlPath, url, paths.altPath, popover.querySelector('#jcpMediaAltInput').value.trim());
        api.recordChange();
      });
    }
    mediaFrame.open();
  };

  const swapColumns = (grid) => {
    const path = grid.dataset.jcpMediaPositionPath;
    if (!path) return;
    const current = api.getPath(api.flatContent, path) === 'left' ? 'left' : 'right';
    const next = current === 'left' ? 'right' : 'left';
    syncFlatProp(path, next);
    grid.classList.remove('jcp-split-layout--media-left', 'jcp-split-layout--media-right');
    grid.classList.add(`jcp-split-layout--media-${next}`);
    const section = grid.closest('.jcp-media-text');
    if (section) {
      section.classList.remove('jcp-media-text--media-left', 'jcp-media-text--media-right');
      section.classList.add(`jcp-media-text--media-${next}`);
    }
    api.recordChange();
  };

  const finishColumnDrag = (grid, e) => {
    if (!dragSourceCol) return;
    const dropCol = document.elementFromPoint(e.clientX, e.clientY)?.closest('[data-jcp-split-col]');
    if (dropCol && dropCol !== dragSourceCol && grid.contains(dropCol)) {
      swapColumns(grid);
    }
    dragSourceCol = null;
    grid.classList.remove('jcp-split-layout--dragging');
    grid.querySelectorAll('.jcp-split-col--dragging, .jcp-split-col--drop-target').forEach((node) => {
      node.classList.remove('jcp-split-col--dragging', 'jcp-split-col--drop-target');
    });
  };

  const bindColumnSwap = () => {
    document.querySelectorAll('[data-jcp-split-path]').forEach((grid) => {
      grid.querySelectorAll('[data-jcp-split-col]').forEach((col) => {
        if (col.querySelector('.jcp-col-drag-handle')) return;

        const handle = document.createElement('div');
        handle.className = 'jcp-col-drag-handle';
        handle.setAttribute('role', 'button');
        handle.setAttribute('tabindex', '0');
        handle.setAttribute('aria-label', 'Drag to swap columns');
        handle.title = 'Drag to swap columns';
        col.prepend(handle);

        handle.addEventListener('pointerdown', (e) => {
          if (!api?.editing()) return;
          e.preventDefault();
          e.stopPropagation();
          dragSourceCol = col;
          handle.setPointerCapture(e.pointerId);
          grid.classList.add('jcp-split-layout--dragging');
          col.classList.add('jcp-split-col--dragging');
        });

        handle.addEventListener('pointermove', (e) => {
          if (!dragSourceCol || dragSourceCol !== col) return;
          grid.querySelectorAll('.jcp-split-col--drop-target').forEach((node) => {
            node.classList.remove('jcp-split-col--drop-target');
          });
          const over = document.elementFromPoint(e.clientX, e.clientY)?.closest('[data-jcp-split-col]');
          if (over && over !== dragSourceCol && grid.contains(over)) {
            over.classList.add('jcp-split-col--drop-target');
          }
        });

        handle.addEventListener('pointerup', (e) => {
          if (dragSourceCol !== col) return;
          finishColumnDrag(grid, e);
          try { handle.releasePointerCapture(e.pointerId); } catch (_) { /* noop */ }
        });

        handle.addEventListener('pointercancel', (e) => {
          if (dragSourceCol !== col) return;
          finishColumnDrag(grid, e);
        });

        handle.addEventListener('keydown', (e) => {
          if (!api?.editing()) return;
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            swapColumns(grid);
          }
        });
      });
    });
  };

  const markMediaHitAreas = () => {
    document.querySelectorAll(
      '.jcp-editable-media-image, .jcp-media-slot, .guarantee-image--empty, .conversion-image-wrapper, .jcp-split-col--media .jcp-media-slot'
    ).forEach((el) => {
      el.classList.add('jcp-media-hit');
    });
    document.querySelectorAll('.guarantee-image-wrapper').forEach((el) => {
      el.classList.add('jcp-media-hit');
    });
  };

  const onDocumentClickCapture = (e) => {
    if (!api?.editing()) return;
    if (e.target.closest('.jcp-col-drag-handle, .jcp-media-popover, .jcp-niche-link-popover')) return;

    const mediaHit = e.target.closest(
      '.jcp-media-hit, .jcp-editable-media-image, .jcp-media-slot, .guarantee-image--empty, .conversion-image-wrapper, .guarantee-image-wrapper, .jcp-split-col--media'
    );

    if (mediaHit) {
      e.preventDefault();
      e.stopPropagation();
      openPopover(mediaHit);
      return;
    }

    const link = e.target.closest('a');
    if (link && link.querySelector('.jcp-editable-media-image, .jcp-media-slot')) {
      e.preventDefault();
      e.stopPropagation();
    }
  };

  let captureBound = false;
  const bindCaptureListeners = () => {
    if (captureBound) return;
    captureBound = true;
    document.addEventListener('click', onDocumentClickCapture, true);
  };

  const init = (editorApi) => {
    api = editorApi;
    bindCaptureListeners();
    markMediaHitAreas();
    bindColumnSwap();
  };

  window.JCP_INIT_PAGE_MEDIA_EDITOR = init;

  window.JCP_REFRESH_PAGE_MEDIA_UI = () => {
    markMediaHitAreas();
    bindColumnSwap();
    if (api?.applyMediaPositionToDom) api.applyMediaPositionToDom();
  };

  if (window.__JCP_EDITOR_API__) {
    init(window.__JCP_EDITOR_API__);
  }
})();
