# JCP Block Page System — Implementation Plan

> **For agentic workers:** Each task is one commit-sized unit. Run PHP lint after PHP changes. Verify HVAC + plumbing pages visually after render changes.

**Goal:** Generalize industry page JSON into a global `blocks[]` system with block registry, unified renderer, global doc import, and new `jcp_page` Marketing Pages CPT — without redesigning any section markup.

**Architecture:** Block registry maps `type` → existing `jcp_niche_render_*` functions. Content stored in `_jcp_page_content` with legacy adapter for `_jcp_niche_content`. Two CPTs share one pipeline.

**Tech stack:** WordPress CPT, PHP render, post meta JSON, REST API, existing `niche-page-editor.js` (generalized), SortableJS (Phase 2 only).

**Spec:** [2026-06-15-jcp-block-page-system-design.md](../specs/2026-06-15-jcp-block-page-system-design.md)

---

## File map

| File | Responsibility |
|------|----------------|
| `inc/page-blocks/registry.php` | Block type catalog |
| `inc/page-blocks/schema.php` | get/save content, legacy adapter |
| `inc/page-blocks/render.php` | `jcp_page_render()` loop |
| `inc/page-blocks/presets.php` | Default block stacks |
| `inc/page-blocks/doc-parser.php` | Moved from niche-landing; outputs `blocks[]` |
| `inc/page-blocks/rest-content.php` | REST `jcp/v1/page/{id}` |
| `inc/page-blocks/admin.php` | Import + JSON meta boxes (both CPTs) |
| `inc/page-blocks/cpt.php` | Register `jcp_page` |
| `single-jcp_page.php` | Marketing page template |
| `inc/niche-landing/*.php` | Thin re-exports; deprecate gradually |
| `inc/admin-theme-docs.php` | Update SOP for block system |
| `assets/js/pages/page-block-editor.js` | Renamed/generalized editor |

---

## Phase 1 — Foundation (ship first)

### Task 1: Block registry

**Files:** create `inc/page-blocks/registry.php`

- Define `jcp_block_registry(): array` returning all block types
- Each entry: `type`, `label`, `description`, `category`, `render` callable name, `doc_sections`, `page_kinds`
- Add `jcp_block_get( string $type ): ?array`
- Add `jcp_block_types_for_kind( string $page_kind ): array`

**Verify:** `php -l inc/page-blocks/registry.php`

---

### Task 2: Schema + legacy adapter

**Files:** create `inc/page-blocks/schema.php`

- `jcp_page_content_meta_key()` → `_jcp_page_content`
- `jcp_page_get_content( int $post_id ): array`
  - Read `_jcp_page_content`, fallback `_jcp_niche_content`
  - If no `blocks` key, call `jcp_page_legacy_to_blocks( $data )`
- `jcp_page_save_content( int $post_id, array $content )`
- `jcp_page_legacy_to_blocks( array $legacy ): array` — map existing section keys to ordered blocks using industry/referral preset order
- `jcp_page_is_content_page( ?int $post_id )` — both CPTs + referral template

**Verify:** PHP one-liner comparing adapted HVAC preset to expected block count (~12)

---

### Task 3: Unified renderer

**Files:** create `inc/page-blocks/render.php`

- `jcp_page_render( int $post_id )`
- Build context: `page_key`, `page_kind`, `post_id`
- Loop `blocks`, lookup registry, call render with `( $props, $context )`
- Wrap existing niche renderers: thin adapters that accept props array instead of full `$c`

**Files:** modify `inc/niche-landing/render.php`

- Refactor each `jcp_niche_render_*` to accept optional props arg OR add `jcp_block_render_*` wrappers that extract props and call existing functions

**Files:** modify `single-jcp_niche_landing.php`

- Replace `jcp_niche_render_page()` with `jcp_page_render()`

**Verify:** HVAC and plumbing pages render identically on local

---

### Task 4: Wire functions.php

**Files:** modify `functions.php`

```php
require_once ... '/inc/page-blocks/registry.php';
require_once ... '/inc/page-blocks/schema.php';
require_once ... '/inc/page-blocks/render.php';
require_once ... '/inc/page-blocks/presets.php';
require_once ... '/inc/page-blocks/rest-content.php';
require_once ... '/inc/page-blocks/cpt.php';
if ( is_admin() ) {
  require_once ... '/inc/page-blocks/admin.php';
}
```

Keep niche-landing requires for now; delegate to page-blocks where duplicated.

---

### Task 5: Generalize doc parser output

**Files:** move/refactor `inc/niche-landing/doc-parser.php` → `inc/page-blocks/doc-parser.php`

- Change `jcp_niche_parse_document()` → `jcp_page_parse_document( $text, $page_key, $page_label, $page_kind )`
- Return `{ version: 1, page_kind, page_key, page_label, blocks: [...] }`
- Each parsed section becomes one block with generated `id` (`b-hero`, `b-faq`, etc.)
- Keep `inc/niche-landing/doc-parser.php` as one-line require for BC

**Verify:** Parse HVAC sample doc → 12 blocks with correct types

---

### Task 6: Marketing Pages CPT

**Files:** create `inc/page-blocks/cpt.php`, `single-jcp_page.php`

- Register `jcp_page` with flat rewrite slug
- `single-jcp_page.php` calls `jcp_page_render()`
- Flush rewrite rules on theme switch (existing pattern from niche CPT)

**Verify:** Create test post `jcp_page` slug `test-lander`, publish, loads renderer

---

### Task 7: Admin import + JSON (both CPTs)

**Files:** create `inc/page-blocks/admin.php`

- Port meta boxes from `inc/niche-landing/admin.php`
- Register on `jcp_niche_landing` AND `jcp_page`
- AJAX `jcp_page_parse_document` returns blocks JSON
- Preset buttons: Industry, Referral, Minimal
- Quick edit + Advanced JSON editing `blocks[]`

**Verify:** Import HVAC doc on new `jcp_page` post, build, save, front-end renders

---

### Task 8: Generalize REST + front-end editor

**Files:** create `inc/page-blocks/rest-content.php`

- Route: `POST/GET jcp/v1/page/(?P<id>\d+)`
- Permission: `edit_post`

**Files:** rename/copy `assets/js/pages/niche-page-editor.js` → `page-block-editor.js`

- Support paths: `blocks.0.props.hero.h1` OR stable `blocks.{id}.props...`
- Update `data-jcp-path` generation in render wrappers

**Files:** modify `inc/enqueue.php`

- Enqueue editor for both CPTs when user can edit

**Verify:** Live edit hero H1 on industry page, save, persists

---

### Task 9: Update SOP docs

**Files:** modify `inc/admin-theme-docs.php`

- Rename/expand to “JCP Page System”
- Document both CPTs, block list, import, editors
- Link to design spec

---

### Task 10: Backward compatibility pass

- Referral program page template still works via legacy adapter
- `jcp_niche_get_content()` → alias `jcp_page_get_content()`
- `jcp_niche_save_content()` → alias `jcp_page_save_content()`
- All existing industry pages unchanged on front-end

**Verify:** `/industries/hvac/`, `/industries/plumbing/`, `/referral-program/` — visual regression check

---

## Phase 2 — Layout editor

### Task 11: Block structure panel

- Admin or front-end “Page structure” sidebar listing blocks
- SortableJS drag to reorder → updates `blocks[]` order
- Save via REST

### Task 12: Add / remove block

- “+ Add block” modal from registry filtered by `page_kind`
- Insert at index; default props from preset snippets
- Delete block with confirm

### Task 13: JCP → Block Library admin page

- Table of blocks with description + preview thumbnail
- Pull preview HTML from ui-library section anchors

---

## Phase 3 — Growth blocks

### Task 14+: New blocks as needed

- `testimonials`, `logo_bar`, `pricing_table`
- Each: registry entry + render function + ui-library section + optional doc section

---

## Testing checklist (Phase 1)

- [ ] HVAC doc import → blocks JSON → publish → matches current HVAC page
- [ ] New `jcp_page` at `/partner-test/` renders from doc import
- [ ] Front-end editor saves block props
- [ ] Legacy JSON posts without `blocks` still render
- [ ] Rank Math SEO unaffected
- [ ] Industries hub still lists `jcp_niche_landing` posts

---

## Commit strategy

1. `feat(page-blocks): add registry and schema with legacy adapter`
2. `feat(page-blocks): unified renderer using existing sections`
3. `feat(page-blocks): generalize doc parser to blocks output`
4. `feat(page-blocks): add jcp_page marketing CPT`
5. `feat(page-blocks): admin import and JSON for both CPTs`
6. `feat(page-blocks): generalize REST and live editor`
7. `docs: update JCP admin SOP for block page system`

---

## Out of scope (Phase 1)

- Drag/drop reorder
- Gutenberg blocks
- Automatic migration of meta key (read adapter only)
- Removing `inc/niche-landing/` directory (deprecate later)
