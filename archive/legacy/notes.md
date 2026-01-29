## Legacy Asset Archive Notes

This folder contains **archived legacy assets** that are no longer used by the runtime,
but are kept for historical reference and easy rollback.

Paths below are given as:

- Original location → Archived location

---

### 1. Legacy JS (theme-root `js/**`)

These files were never enqueued in the current system. All runtime JS now
loads from `assets/js/**` via `inc/enqueue.php` and the `jcp_core_enqueue_script`
helper (which resolves to `assets/` when no theme-root file exists).

- `js/core/nav.js` → `archive/legacy/js/core/nav.js`

**Why safe to archive**

- `REFACTOR_AUDIT.md` marks `js/**` as legacy: not referenced by any enqueue.
- `inc/enqueue.php` uses the handle `jcp-core-nav` with path `js/core/jcp-nav.js`,
  which resolves to `assets/js/core/jcp-nav.js` (NOT `js/core/nav.js`).
- No PHP, JS, or CSS references `js/core/nav.js` directly.

**How to restore**

- Move back if ever needed:
  - From: `archive/legacy/js/core/nav.js`
  - To:   `js/core/nav.js`

---

### 2. Duplicate CSS tree (`assets/css/**`)

This tree is a **legacy duplicate** of the primary `/css/**` styles. Current
runtime enqueues point to `/css/...` (theme-root). The helper functions
only fall back to `assets/css/...` when the root file is missing.

Archived files:

- `assets/css/base.css` → `archive/legacy/css/base.css`
- `assets/css/buttons.css` → `archive/legacy/css/buttons.css`
- `assets/css/components.css` → `archive/legacy/css/components.css`
- `assets/css/design-system-page.css` → `archive/legacy/css/design-system-page.css`
- `assets/css/design-system.css` → `archive/legacy/css/design-system.css`
- `assets/css/layout.css` → `archive/legacy/css/layout.css`
- `assets/css/pages/home.css` → `archive/legacy/css/pages/home.css`
- `assets/css/pages/early-access.css` → `archive/legacy/css/pages/early-access.css`
- `assets/css/pages/pricing.css` → `archive/legacy/css/pages/pricing.css`
- `assets/css/pages/home/hero.css` → `archive/legacy/css/pages/home/hero.css`
- `assets/css/pages/home/how-it-works.css` → `archive/legacy/css/pages/home/how-it-works.css`
- `assets/css/pages/home/features.css` → `archive/legacy/css/pages/home/features.css`
- `assets/css/pages/home/outcomes.css` → `archive/legacy/css/pages/home/outcomes.css`
- `assets/css/pages/home/demo-preview.css` → `archive/legacy/css/pages/home/demo-preview.css`
- `assets/css/pages/home/who-its-for.css` → `archive/legacy/css/pages/home/who-its-for.css`
- `assets/css/pages/home/pricing-preview.css` → `archive/legacy/css/pages/home/pricing-preview.css`
- `assets/css/pages/home/faq.css` → `archive/legacy/css/pages/home/faq.css`
- `assets/css/pages/home/final-cta.css` → `archive/legacy/css/pages/home/final-cta.css`

**Why safe to archive**

- `REFACTOR_AUDIT.md`:
  - Flags `assets/css/**` as a **duplicate CSS tree**.
  - Notes that runtime enqueues always use `/css/**`, with `assets/css/**`
    only reachable as a fallback if the root file is deleted.
- `inc/enqueue.php` enqueues only:
  - `css/base.css`, `css/layout.css`, `css/buttons.css`, `css/components.css`,
    `css/utilities.css`, and `css/pages/**`.
- No enqueues or imports reference `assets/css/...` directly.
- The active, maintained styles live in `/css/**` and `css/sections.css`.

**Runtime behavior**

- Because all `/css/**` files still exist at the theme root, the helper
  functions never fall back to `assets/css/**`. Moving this tree does **not**
  change current runtime behavior.

**How to restore**

- Move files back if you ever intentionally want the fallback behavior:
  - From: `archive/legacy/css/...`
  - To:   `assets/css/...`

---

### 3. Legacy directory CSS (`assets/directory/profile.css`)

The directory page and company profile now use consolidated CSS under
`/css/pages/` (`directory-consolidated.css`, `profile-consolidated.css`).

Archived file:

- `assets/directory/profile.css` → `archive/legacy/css/directory/profile.css`

**Why safe to archive**

- `REFACTOR_AUDIT.md`:
  - Marks `assets/directory/*.css` as **legacy**; runtime enqueues use
    `/css/pages/directory-consolidated.css` and `/css/pages/profile-consolidated.css`.
- `css/pages/profile-consolidated.css` does **not** import `assets/directory/profile.css`.
- `inc/enqueue.php` never enqueues any `assets/directory/*.css` files.
- HTML templates in `assets/directory/profile.html` reference `profile.css`
  via `<link>` tags, but `assets/core/jcp-render.js` strips all `<link>`
  elements when loading templates, so those references are not used at runtime.

**Important note**

- `assets/directory/directory.css` is **not** archived:
  - It is still imported by `css/pages/directory-consolidated.css`:
    - `@import url(\"../../assets/directory/directory.css\");`
  - That file remains part of the active runtime CSS pipeline.

**How to restore**

- Move back if needed:
  - From: `archive/legacy/css/directory/profile.css`
  - To:   `assets/directory/profile.css`

---

### Rollback / safety summary

- **No enqueues or imports were changed** in this stage.
- Only files that are **not referenced by current runtime paths** were moved.
- To fully roll back Stage 4:
  1. Move `archive/legacy/js/core/nav.js` back to `js/core/nav.js`.
  2. Move all files from `archive/legacy/css/` back to their original locations:
     - `assets/css/**`
     - `assets/directory/profile.css`

All moves are reversible with simple `mv` operations; no file contents were modified.

