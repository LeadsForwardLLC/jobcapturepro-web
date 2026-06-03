(() => {
  const cfg = window.JCP_NICHE_EDITOR;
  if (!cfg || !cfg.postId || !cfg.restUrl) return;

  const panel = document.createElement('aside');
  panel.className = 'jcp-niche-edit-panel';
  panel.setAttribute('aria-label', 'Edit industry page');
  panel.innerHTML = `
    <h2>Edit page</h2>
    <div class="jcp-niche-edit-tabs" role="tablist"></div>
    <form id="jcpNicheEditForm"></form>
    <p style="margin-top:1rem;display:flex;gap:0.5rem;flex-wrap:wrap;">
      <button type="button" class="btn btn-primary" id="jcpNicheSave">Save</button>
      <button type="button" class="btn btn-secondary" id="jcpNicheClose">Close</button>
    </p>
    <p id="jcpNicheEditStatus" style="font-size:0.875rem;color:#64748b;"></p>
  `;

  const bar = document.createElement('div');
  bar.className = 'jcp-niche-edit-bar';
  bar.innerHTML = `
    <strong>Industry page editor</strong>
    <button type="button" class="btn btn-secondary" id="jcpNicheOpenPanel">Edit content</button>
    <a href="${cfg.adminUrl || '#'}" class="btn btn-secondary">WP Admin</a>
    <a href="${cfg.url || '#'}" class="btn btn-secondary">View live</a>
  `;

  document.body.appendChild(panel);
  document.body.appendChild(bar);
  document.body.classList.add('jcp-niche-editing');

  const tabs = [
    { id: 'seo', label: 'SEO' },
    { id: 'hero', label: 'Hero' },
    { id: 'what', label: 'What it is' },
    { id: 'how', label: 'How it works' },
    { id: 'final', label: 'Final CTA' },
  ];

  const tabEl = panel.querySelector('.jcp-niche-edit-tabs');
  const form = panel.querySelector('#jcpNicheEditForm');
  let content = {};
  let activeTab = 'hero';

  tabs.forEach((t) => {
    const b = document.createElement('button');
    b.type = 'button';
    b.textContent = t.label;
    b.dataset.tab = t.id;
    if (t.id === activeTab) b.classList.add('is-active');
    b.addEventListener('click', () => {
      activeTab = t.id;
      tabEl.querySelectorAll('button').forEach((x) => x.classList.toggle('is-active', x.dataset.tab === activeTab));
      renderForm();
    });
    tabEl.appendChild(b);
  });

  const field = (label, name, value, rows) => {
    const wrap = document.createElement('div');
    wrap.dataset.field = name;
    const lab = document.createElement('label');
    lab.textContent = label;
    lab.setAttribute('for', name);
    let input;
    if (rows) {
      input = document.createElement('textarea');
      input.rows = rows;
    } else {
      input = document.createElement('input');
      input.type = 'text';
    }
    input.id = name;
    input.name = name;
    input.value = value || '';
    wrap.appendChild(lab);
    wrap.appendChild(input);
    return wrap;
  };

  const renderForm = () => {
    form.innerHTML = '';
    const c = content;
    if (activeTab === 'seo') {
      form.appendChild(field('SEO title', 'seo_title', c.seo?.title));
      form.appendChild(field('Meta description', 'seo_desc', c.seo?.meta_description, 3));
    }
    if (activeTab === 'hero') {
      form.appendChild(field('H1', 'hero_h1', c.hero?.h1));
      form.appendChild(field('Subheadline', 'hero_sub', c.hero?.subheadline, 4));
      form.appendChild(field('Trust line', 'hero_trust', c.hero?.trust_line));
      form.appendChild(field('Primary CTA label', 'hero_cta1', c.hero?.cta_primary?.label));
      form.appendChild(field('Secondary CTA label', 'hero_cta2', c.hero?.cta_secondary?.label));
    }
    if (activeTab === 'what') {
      form.appendChild(field('Headline', 'what_h', c.what_it_is?.headline));
      form.appendChild(field('Subheadline', 'what_sub', c.what_it_is?.subheadline, 3));
      form.appendChild(field('Closing', 'what_close', c.what_it_is?.closing, 3));
    }
    if (activeTab === 'how') {
      form.appendChild(field('Headline', 'how_h', c.how_it_works?.headline));
      form.appendChild(field('Subheadline', 'how_sub', c.how_it_works?.subheadline, 3));
    }
    if (activeTab === 'final') {
      form.appendChild(field('Headline', 'final_h', c.final_cta?.headline));
      form.appendChild(field('Subheadline', 'final_sub', c.final_cta?.subheadline, 3));
      form.appendChild(field('Button label', 'final_btn', c.final_cta?.cta_primary?.label));
      form.appendChild(field('Button note', 'final_note', c.final_cta?.cta_note));
    }
  };

  const collectForm = () => {
    const g = (n) => form.querySelector(`[name="${n}"]`)?.value?.trim() || '';
    content.seo = content.seo || {};
    content.hero = content.hero || {};
    content.what_it_is = content.what_it_is || {};
    content.how_it_works = content.how_it_works || {};
    content.final_cta = content.final_cta || {};
    content.hero.cta_primary = content.hero.cta_primary || {};
    content.hero.cta_secondary = content.hero.cta_secondary || {};
    content.final_cta.cta_primary = content.final_cta.cta_primary || {};

    if (activeTab === 'seo' || true) {
      if (g('seo_title')) content.seo.title = g('seo_title');
      if (g('seo_desc')) content.seo.meta_description = g('seo_desc');
    }
    if (g('hero_h1')) content.hero.h1 = g('hero_h1');
    if (g('hero_sub')) content.hero.subheadline = g('hero_sub');
    if (g('hero_trust')) content.hero.trust_line = g('hero_trust');
    if (g('hero_cta1')) content.hero.cta_primary.label = g('hero_cta1');
    if (g('hero_cta2')) content.hero.cta_secondary.label = g('hero_cta2');
    if (g('what_h')) content.what_it_is.headline = g('what_h');
    if (g('what_sub')) content.what_it_is.subheadline = g('what_sub');
    if (g('what_close')) content.what_it_is.closing = g('what_close');
    if (g('how_h')) content.how_it_works.headline = g('how_h');
    if (g('how_sub')) content.how_it_works.subheadline = g('how_sub');
    if (g('final_h')) content.final_cta.headline = g('final_h');
    if (g('final_sub')) content.final_cta.subheadline = g('final_sub');
    if (g('final_btn')) content.final_cta.cta_primary.label = g('final_btn');
    if (g('final_note')) content.final_cta.cta_note = g('final_note');
  };

  const status = panel.querySelector('#jcpNicheEditStatus');

  const load = async () => {
    const res = await fetch(cfg.restUrl, { credentials: 'same-origin', headers: { 'X-WP-Nonce': cfg.nonce } });
    const data = await res.json();
    content = data.content || {};
    renderForm();
  };

  panel.querySelector('#jcpNicheSave').addEventListener('click', async () => {
    collectForm();
    status.textContent = 'Saving…';
    const res = await fetch(cfg.restUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': cfg.nonce },
      body: JSON.stringify({ content }),
    });
    if (res.ok) {
      status.textContent = 'Saved. Reloading…';
      window.location.reload();
    } else {
      status.textContent = 'Save failed.';
    }
  });

  panel.querySelector('#jcpNicheClose').addEventListener('click', () => panel.classList.remove('is-open'));
  document.getElementById('jcpNicheOpenPanel').addEventListener('click', () => {
    panel.classList.add('is-open');
    load();
  });

  if (new URLSearchParams(window.location.search).get('jcp_edit') === '1') {
    panel.classList.add('is-open');
    load();
  }
})();
