# JCP Block Page System — Design Spec

**Date:** 2026-06-15  
**Status:** Approved direction (hybrid CPT + unified blocks)  
**Goal:** Scalable marketing site architecture for high-volume trade pages, campaign landers, and internal pages — one design system, one editor, one import format.

---

## Executive summary

JobCapturePro already builds pages from **structured JSON + PHP section renderers** (hero, benefits, FAQ, etc.). The next step is not a new page builder — it is **generalizing that system into a global block library** with:

1. **Unified content model** — ordered `blocks[]` array in post meta  
2. **Two CPTs for scale** — Industries (programmatic trades) + Marketing Pages (everything else)  
3. **One block registry** — maps block types to existing render functions and CSS  
4. **One doc import format** — writers paste/upload the same template everywhere  
5. **One live editor** — click-to-edit today; drag/reorder + add block in Phase 2  

This supports a $100M SaaS marketing operation: dozens of trade verticals, campaign landers, partner pages, and feature pages — without design drift or duplicated theme code.

---

## Recommendation: hybrid CPT architecture

| Content type | CPT | URL pattern | Why separate |
|--------------|-----|-------------|--------------|
| **Trade / industry pages** | `jcp_niche_landing` (existing) | `/industries/{slug}/` | Programmatic SEO hub, “By Trade” nav, high volume, shared preset |
| **Marketing / internal pages** | `jcp_page` (new) | `/{slug}/` (flat, clean) | Pricing variants, partners, features, comparisons — not mixed with WP blog/pages |
| **Simple static** | WordPress `page` | varies | Legal, contact wrappers — optional migration later |

**Do not** put everything in WordPress Pages. At scale, the Pages menu becomes unmanageable and URL strategy gets messy.

**Do not** merge Industries into Marketing Pages. The `/industries/` hub, archive, and trade workflow are already correct for programmatic vertical expansion.

**Do** use **one JSON schema and one renderer** underneath both CPTs.

Referral program: migrate to `jcp_page` with `preset: referral` (or keep page template short-term via legacy adapter).

---

## Unified content model

### Meta key

`_jcp_page_content` (new canonical key).  
Legacy `_jcp_niche_content` supported via read-time adapter until migrated.

### Top-level shape

```json
{
  "version": 1,
  "page_kind": "industry",
  "page_key": "hvac",
  "page_label": "HVAC",
  "preset": "industry",
  "seo": {
    "keywords": []
  },
  "settings": {
    "hide_breadcrumb": false,
    "show_hub_breadcrumb": true
  },
  "blocks": [
    { "id": "b1", "type": "hero", "props": { "h1": "...", "subheadline": "..." } },
    { "id": "b2", "type": "what_it_is", "props": { ... } },
    { "id": "b3", "type": "how_it_works", "props": { ... } }
  ]
}
```

| Field | Purpose |
|-------|---------|
| `version` | Migration safety |
| `page_kind` | `industry` \| `marketing` \| `referral` — drives breadcrumb, CTA UTM defaults |
| `page_key` | Slug fallback for onboarding UTM (`industry_hvac`, `page_partner`) |
| `preset` | Default block stack when creating new page (`industry`, `referral`, `minimal`) |
| `blocks[]` | Ordered list; renderer loops this array |
| `blocks[].id` | Stable ID for drag/drop and editor targeting |
| `blocks[].type` | Registry key |
| `blocks[].props` | Block-specific payload (same shapes as today’s section JSON) |

### Legacy adapter

On read, if content has no `blocks` key but has `hero`, `what_it_is`, etc., convert to `blocks[]` in memory (no destructive migration required on day one).

Industry preset block order (default):

`breadcrumb` → `hero` → `what_it_is` → `core_mechanic` → `how_it_works` → `check_ins` → `problem` → `benefits` → `differentiation` → `who_its_for` → `faq` → `final_cta`

Referral preset omits `problem`, `differentiation`, `who_its_for`; adds `cta_band`, `commission`, `partners`, `share`.

---

## Block registry (single source of truth)

**File:** `inc/page-blocks/registry.php`

Each block definition:

```php
[
  'type'         => 'hero',
  'label'        => 'Hero',
  'description'  => 'H1, subheadline, CTAs, trust line',
  'category'     => 'header',
  'render'       => 'jcp_block_render_hero',      // wraps jcp_niche_render_hero
  'doc_sections' => [ 'HERO' ],
  'props_schema' => [ ... ],                       // for editor validation
  'preview'      => 'hero',                        // ui-library anchor
  'page_kinds'   => [ 'industry', 'marketing', 'referral' ],
]
```

### Initial block catalog (Phase 1)

Reuse existing renderers — **no new HTML/CSS**:

| Block type | Source renderer | Doc section |
|------------|-----------------|-------------|
| `breadcrumb` | `jcp_niche_render_breadcrumb` | — |
| `hero` | `jcp_niche_render_hero` | HERO |
| `what_it_is` | `jcp_niche_render_what_it_is` | WHAT IT IS |
| `core_mechanic` | partials meta strip | CORE MECHANIC |
| `how_it_works` | `jcp_niche_render_how_it_works` | HOW IT WORKS |
| `check_ins` | `jcp_niche_render_check_ins` | CHECK-INS |
| `problem` | `jcp_niche_render_problem` | PROBLEM |
| `benefits` | `jcp_niche_render_benefits` | BENEFITS |
| `differentiation` | `jcp_niche_render_differentiation` | DIFFERENTIATION |
| `who_its_for` | `jcp_niche_render_who_its_for` | WHO IT'S FOR |
| `faq` | `jcp_niche_render_faq` | FAQ |
| `final_cta` | `jcp_niche_render_final_cta` | FINAL CTA |
| `cta_band` | `jcp_niche_render_cta_band` | — |
| `commission` | `jcp_niche_render_commission` | — |
| `partners` | `jcp_niche_render_partners` | — |
| `share` | `jcp_niche_render_share` | — |

Future blocks (Phase 3+): `logo_bar`, `testimonials`, `pricing_table`, `comparison_table` — added once to registry + ui-library, available everywhere.

---

## Rendering pipeline

```
jcp_page_get_content( $post_id )
  → legacy adapter if needed
  → returns normalized { blocks, page_kind, page_key, ... }

jcp_page_render( $post_id )
  → foreach block in blocks
      → registry[type].render( props, context )
  → context: page_key, page_kind, post_id, block_id
```

**Context object** replaces passing `$niche_key` everywhere; `page_key` remains for CTA UTM resolution.

Templates:

- `single-jcp_niche_landing.php` → calls `jcp_page_render()`
- `single-jcp_page.php` (new) → calls `jcp_page_render()`
- `page-referral-program.php` → calls `jcp_page_render()` via adapter

---

## Document import (global)

**Move/generalize:** `inc/niche-landing/doc-parser.php` → `inc/page-blocks/doc-parser.php`

- Same writer template (HERO, WHAT IT IS, etc.)
- Output: `{ blocks: [...] }` instead of flat section keys
- `Primary Keyword` → `seo.keywords` (hub search for industries only)
- SEO title/description remain in **Rank Math**, not JSON

Import UI on **both** CPT edit screens: paste, .docx, .txt, “Build page from document”.

---

## Editors

### Phase 1 (extend existing)

- **Backend:** Import meta box + Quick Edit + Advanced JSON (blocks array)
- **Front-end:** Existing toolbar (`niche-page-editor.js` → rename `page-block-editor.js`)
  - `data-jcp-path` becomes `blocks.{index}.props.{field}` or stable `blocks.{id}.props.{field}`
  - REST endpoint generalized: `jcp/v1/page/{id}`

### Phase 2 (layout editor)

- **Reorder blocks:** SortableJS on admin canvas or front-end “Structure” panel
- **Add block:** Modal listing registry blocks with descriptions
- **Remove block:** Per-block delete with confirm
- **Duplicate block:** Copy block in stack

No React, no Gutenberg — stays lightweight and on-brand.

### Phase 3 (block library admin)

- **JCP → Block Library** admin page
- Lists all blocks with live preview (iframe or inline from ui-library snippets)
- Links to writer doc section names
- Documents which `page_kinds` each block supports

---

## CPT: `jcp_page` (Marketing Pages)

```php
register_post_type( 'jcp_page', [
  'rewrite' => [ 'slug' => '%jcp_page%', 'with_front' => false ], // flat URLs
  'has_archive' => false,
  'menu_icon' => 'dashicons-media-document',
  'labels' => [ 'name' => 'Marketing Pages', ... ],
]);
```

Admin list columns: URL, preset, block count, last edited.

No public archive — individual landers only.

---

## SEO & analytics

- **Rank Math** on every CPT post (unchanged)
- **UTM defaults** from `page_kind` + `page_key` via `jcp_core_onboarding_utm_defaults()`
- **Matomo CTA tracking** unchanged on render attributes

---

## Scalability rationale ($100M SaaS)

| Need | How this architecture scales |
|------|------------------------------|
| 50+ trade pages | Industries CPT + hub + doc import at volume |
| Campaign landers | Marketing Pages CPT, flat URLs, preset per campaign |
| Design consistency | One registry, one CSS system, ui-library as reference |
| Content team throughput | Same writer doc format; no JSON hand-editing required |
| Engineering velocity | New block = one registry entry + one render function |
| No vendor lock-in | JSON in post meta, PHP render, no page-builder plugin |
| Performance | Server-rendered HTML, no client-side layout engine on public pages |

---

## What we explicitly avoid

- Gutenberg as primary builder (fights JSON model, inconsistent with marketing sections)
- Elementor / Webflow-style visual builders (heavy, design drift)
- Per-page custom templates (doesn't scale)
- Duplicate render markup for “marketing” vs “industry”

---

## Migration strategy

1. **Read-time adapter** — old JSON works immediately  
2. **Save-time upgrade** — optional “Upgrade to blocks format” button in admin  
3. **Batch migration** — WP-CLI command `wp jcp migrate-content` for all posts  
4. **Referral program** — convert `page-referral-program` content to `jcp_page` post or keep template with adapter indefinitely  

No big-bang cutover. Industry pages stay live throughout.

---

## File structure (target)

```
inc/page-blocks/
  registry.php      # Block catalog
  schema.php          # get/save/adapter
  render.php          # Loop + context
  doc-parser.php      # Writer doc → blocks
  presets.php         # industry, referral, minimal
  rest-content.php    # REST API for editor
  admin.php           # Import + JSON meta boxes
  cpt.php             # jcp_page registration

inc/niche-landing/    # Thin wrappers deprecating over time → re-export from page-blocks
```

---

## Success criteria

- [ ] New marketing page created via doc import in &lt; 10 minutes  
- [ ] HVAC/plumbing pages render identically before/after blocks migration  
- [ ] One block added to registry appears in import + editor library  
- [ ] Front-end editor saves `blocks[]` without breaking layout  
- [ ] SOP documents both CPTs and block workflow  

---

## Open decisions (defaults chosen)

| Question | Decision |
|----------|----------|
| Page template vs CPT for marketing? | **New `jcp_page` CPT** |
| Keep Industries separate? | **Yes** |
| Meta key rename? | **`_jcp_page_content`** with legacy read adapter |
| Drag/drop in Phase 1? | **No** — Phase 2 |
| Gutenberg? | **No** |
