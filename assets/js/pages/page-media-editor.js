/**
 * Front-end media picker, per-instance alt text, type toggle, and column swap.
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
    const urlPath = el.dataset.jcpMediaUrlPath
      || slot?.dataset.jcpMediaUrlPath
      || (slot?.dataset.jcpMediaPath ? `${slot.dataset.jcpMediaPath}.media_url` : null);
    const altPath = el.dataset.jcpMediaAltPath
      || slot?.dataset.jcpMediaAltPath
      || (slot?.dataset.jcpMediaPath ? `${slot.dataset.jcpMediaPath}.media_alt` : null);
    const typePath = slot?.dataset.jcpMediaPath
      ? `${slot.dataset.jcpMediaPath}.media_type`
      : (el.dataset.jcpMediaUrlPath ? el.dataset.jcpMediaUrlPath.replace(/\.(media_url|image_url)$/, '.media_type') : null);
    return { slot, urlPath, altPath, typePath, basePath: slot?.dataset.jcpMediaPath || '' };
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
      const slot = document.querySelector(`.jcp-media-slot[data-jcp-media-path="${urlPath.split('.').slice(0, -1).join('.')}"]`);
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
      if (popover.contains(e.target) || e.target.closest('.jcp-editable-media-image, .jcp-media-slot, .guarantee-image--empty, .jcp-editable-media-wrap')) return;
      closePopover();
    });

    return popover;
  };

  const onTypeSelectChange = () => {
    const type = popover.querySelector('#jcpMediaTypeSelect').value;
    const videoField = popover.querySelector('.jcp-media-popover__field--video');
    const replaceBtn = popover.querySelector('#jcpMediaReplaceBtn');
    videoField.hidden = type !== 'video';
    if (type === 'video') videoField.removeAttribute('hidden');
    else videoField.setAttribute('hidden', '');
    replaceBtn.hidden = type === 'phone_mockup';
    if (type === 'phone_mockup') replaceBtn.setAttribute('hidden', '');
    else replaceBtn.removeAttribute('hidden');
  };

  const openPopover = (el) => {
    if (!api?.editing()) return;
    activeTarget = el;
    const paths = getMediaPaths(el);
    if (!paths.urlPath && !paths.altPath) return;

    ensurePopover();
    const types = allowedTypes(el);
    const select = popover.querySelector('#jcpMediaTypeSelect');
    select.innerHTML = types.map((t) => `<option value="${t.value}">${t.label}</option>`).join('');

    const type = currentMediaType(paths);
    if (types.some((t) => t.value === type)) select.value = type;
    else select.value = types[0]?.value || 'image';

    const altVal = paths.altPath ? (api.getPath(api.flatContent, paths.altPath) || '') : '';
    popover.querySelector('#jcpMediaAltInput').value = altVal;

    const urlVal = paths.urlPath ? (api.getPath(api.flatContent, paths.urlPath) || '') : '';
    popover.querySelector('#jcpMediaVideoUrlInput').value = urlVal;

    onTypeSelectChange();

    const rect = el.getBoundingClientRect();
    popover.hidden = false;
    popover.removeAttribute('hidden');
    popover.style.top = `${Math.min(window.innerHeight - 280, rect.bottom + 8)}px`;
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

  const bindMediaTargets = () => {
    document.querySelectorAll('.jcp-editable-media-image, .guarantee-image--empty, .jcp-media-slot').forEach((el) => {
      if (el.dataset.jcpMediaBound) return;
      el.dataset.jcpMediaBound = '1';
      el.addEventListener('click', (e) => {
        if (!api?.editing()) return;
        e.preventDefault();
        e.stopPropagation();
        openPopover(el.classList.contains('jcp-media-slot') ? el.querySelector('.jcp-editable-media-image, .jcp-media-variant:not([hidden])') || el : el);
      });
    });
  };

  const bindColumnSwap = () => {
    document.querySelectorAll('[data-jcp-split-path]').forEach((grid) => {
      if (grid.dataset.jcpSplitBound) return;
      grid.dataset.jcpSplitBound = '1';

      grid.querySelectorAll('[data-jcp-split-col]').forEach((col) => {
        if (col.querySelector('.jcp-col-swap-handle')) return;
        const handle = document.createElement('button');
        handle.type = 'button';
        handle.className = 'jcp-col-swap-handle';
        handle.title = 'Drag onto the other column to swap sides';
        handle.setAttribute('aria-label', 'Swap columns');
        handle.textContent = '⇄';
        col.appendChild(handle);

        handle.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          if (!api?.editing()) return;
          swapColumns(grid);
        });

        col.addEventListener('dragover', (e) => {
          if (!api?.editing()) return;
          e.preventDefault();
          col.classList.add('jcp-split-col--drop-target');
        });
        col.addEventListener('dragleave', () => col.classList.remove('jcp-split-col--drop-target'));
        col.addEventListener('drop', (e) => {
          if (!api?.editing()) return;
          e.preventDefault();
          col.classList.remove('jcp-split-col--drop-target');
          swapColumns(grid);
        });
      });

      const mediaCol = grid.querySelector('[data-jcp-split-col="media"]');
      if (mediaCol) {
        mediaCol.setAttribute('draggable', 'true');
        mediaCol.addEventListener('dragstart', (e) => {
          if (!api?.editing()) {
            e.preventDefault();
            return;
          }
          e.dataTransfer.effectAllowed = 'move';
          grid.classList.add('jcp-split-layout--dragging');
        });
        mediaCol.addEventListener('dragend', () => grid.classList.remove('jcp-split-layout--dragging'));
      }
    });
  };

  window.JCP_INIT_PAGE_MEDIA_EDITOR = (editorApi) => {
    api = editorApi;
    bindMediaTargets();
    bindColumnSwap();
  };

  window.JCP_REFRESH_PAGE_MEDIA_UI = () => {
    bindMediaTargets();
    bindColumnSwap();
    if (api?.applyMediaPositionToDom) api.applyMediaPositionToDom();
  };
})();
