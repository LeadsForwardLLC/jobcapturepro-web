# JobCapturePro Core Theme - Master Documentation

**Last Updated:** January 28, 2026  
**Version:** 1.2  
**For:** Project Managers, Developers, Designers

---

## 📋 TABLE OF CONTENTS

1. [Project Overview](#project-overview)
2. [Architecture & Structure](#architecture--structure)
3. [File Organization](#file-organization)
4. [Asset Management](#asset-management)
5. [Template System](#template-system)
6. [Development Guidelines](#development-guidelines)
7. [Current Status](#current-status)
8. [Quick Reference](#quick-reference)
9. [Setup & Integrations](#setup--integrations)

---

## 🎯 PROJECT OVERVIEW

### What is This Theme?
WordPress theme for JobCapturePro public website, directory, and estimator. The theme uses **client-side rendering** for most pages - WordPress acts as the host/CMS, while JavaScript handles UI rendering.

### Key Characteristics
- **Hybrid Architecture**: WordPress PHP templates + JavaScript rendering + static HTML templates
- **Modular CSS**: Design tokens → base → layout → components → sections → utilities → pages
- **Organized JavaScript**: Core → Features → Pages structure
- **Clean Structure**: All unused files removed, everything documented

---

## 🏗️ ARCHITECTURE & STRUCTURE

### Rendering Patterns

#### Pattern 1: JavaScript String Templates (Homepage, Pricing, Early Access)
```php
// page-home.php
<?php get_header(); ?>
<div id="jcp-app" data-jcp-page="home"></div>
<?php get_footer(); ?>
```
- JavaScript (`js/pages/home.js`) renders HTML via string templates
- Content is dynamically generated client-side
- Used for: Homepage, Pricing, Early Access

#### Pattern 2: Static HTML Templates (Demo, Directory, Estimate, Survey)
```php
// page-demo.php
<?php get_header(); ?>
<div id="jcp-app" data-jcp-page="demo"></div>
<?php get_footer(); ?>
```
- JavaScript (`js/core/jcp-render.js`) loads HTML files via XMLHttpRequest
- HTML templates in `assets/demo/index.html`, `assets/directory/index.html`, etc.
- Used for: Demo, Directory, Estimate, Survey, Company Profile

#### Pattern 3: WordPress Content Templates (page.php, home.php, single.php)
- **Standard page** (`page.php`): One section, one container; title and body in same block to avoid double vertical padding.
- **Blog archive** (`home.php`): One section; blog title/tagline and post grid in same block.
- **Single post** (`single.php`): One section; title, meta (author + avatar, date, categories), content, tags, post nav, comments in one block. Meta uses dot separators; author links to author archive with round Gravatar.

#### Pattern 4: PHP Templates (Design System, UI Library)
```php
// page-design-system.php
<?php get_header(); ?>
<!-- Full PHP-rendered content -->
<?php get_footer(); ?>
```
- Traditional WordPress PHP templates
- Used for: Design System documentation, UI Library

#### Blog & single post styling (`css/pages/blog.css`)
- **Single post meta:** Author (round 36px avatar + name link), date, categories; dot separators; compact spacing.
- **Comments:** One divider line before comments; reduced spacing; compact form (smaller inputs, 4-row textarea); comment list and form margins tightened.
- **Post navigation:** No top border (single divider is comments only); reduced margin above.

---

## 📁 FILE ORGANIZATION

### Root Directory Structure

```
jobcapturepro-core/
├── assets/              # Static assets (JS, HTML templates, icons, images)
├── css/                 # All stylesheets
├── inc/                 # PHP includes (enqueue, helpers, ACF config)
├── templates/           # PHP template parts
├── archive/             # Legacy/archived files (not used)
├── *.php                # WordPress template files (MUST be in root)
└── DOCUMENTATION.md     # This file (master documentation)
```

### CSS Structure

```
css/
├── tokens.css           # Design tokens (CSS variables) - colors, spacing, typography
├── base.css             # Resets, typography defaults, global element rules
├── layout.css           # Containers, grids, section spacing, responsive primitives
├── buttons.css          # Empty shim (button styles moved to components.css)
├── components.css       # Buttons, cards, badges, pills, accordions (single source of truth)
├── sections.css          # Homepage section styles (hero, FAQ, CTA, etc.)
├── utilities.css        # Helper classes (text-center, spacing utilities)
└── pages/
    ├── home.css         # Homepage-specific overrides only
    ├── pricing.css      # Pricing-specific overrides only
    ├── early-access.css # Early access-specific overrides only
    ├── directory-consolidated.css  # Directory page styles
    ├── profile-consolidated.css     # Company profile styles
    ├── demo.css         # Demo page styles
    ├── estimate.css    # Estimate page styles
    └── survey.css       # Survey page styles
```

**CSS Loading Order:**
```
base.css → layout.css → buttons.css → components.css → utilities.css → sections.css → page-specific.css
```

### JavaScript Structure

```
assets/js/
├── core/
│   ├── jcp-render.js   # Template loader (loads HTML files via AJAX)
│   └── jcp-nav.js      # Global navigation behavior
├── features/
│   ├── demo/
│   │   └── jcp-demo.js
│   ├── directory/
│   │   ├── directory.js
│   │   ├── profile.js
│   │   └── directory-integration.js
│   ├── estimate/
│   │   ├── estimate-builder.js
│   │   ├── analytics.js
│   │   └── requests.js
│   ├── survey/
│   │   └── survey.js
│   └── faq.js
└── pages/
    ├── home.js          # Homepage renderer (string templates)
    ├── pricing.js        # Pricing page renderer (string templates)
    └── early-access.js   # Early access renderer (string templates)
```

### Assets Structure

```
assets/
├── demo/
│   ├── index.html       # Demo page HTML template
│   └── leaflet/         # Leaflet mapping library (CSS, JS, images)
├── directory/
│   ├── index.html       # Directory listing HTML template
│   ├── profile.html     # Company profile HTML template
│   └── directory.css    # Directory-specific CSS (imported)
├── estimate/
│   ├── index.html       # Estimate builder HTML template
│   ├── estimate-builder.css  # Estimate CSS (imported)
│   └── fontawesome/     # FontAwesome icon library
├── survey/
│   └── index.html       # Survey HTML template
├── js/                  # All JavaScript (see JS structure above)
└── shared/
    ├── assets/
    │   ├── demo.css     # Shared demo CSS (imported)
    │   └── survey.css   # Survey CSS (imported)
    ├── icons/           # 3,200+ Lucide icon JSON files
    ├── img/             # Shared images
    └── video/           # Shared videos
```

### Template Structure

```
Root (WordPress REQUIRES these in root):
├── index.php                    # Fallback template
├── header.php                   # Wrapper → templates/global/header.php
├── footer.php                   # Wrapper → templates/global/footer.php
├── functions.php                # Theme bootstrap
├── page-home.php                # Homepage (JS-rendered)
├── page-pricing.php             # Pricing (JS-rendered)
├── page-early-access.php        # Early access (JS-rendered)
├── page-demo.php                # Demo (loads HTML template)
├── page-directory.php           # Directory (loads HTML template)
├── page-estimate.php            # Estimate (loads HTML template)
├── page-company.php             # Company (loads HTML template)
├── page-design-system.php       # Design system docs (PHP-rendered)
├── page-ui-library.php          # UI library docs (PHP-rendered)
└── single-jcp_company.php       # Company profile (loads HTML template)

templates/
├── global/
│   ├── header.php               # Full HTML header (called by root header.php)
│   ├── footer.php               # Full HTML footer (called by root footer.php)
│   └── nav.php                  # Navigation (called by header.php)
└── partials/
    └── nav.php                  # Nav partial (called by global/nav.php)
```

**Why Root Files Can't Be Moved:**
WordPress template hierarchy **REQUIRES** `page-*.php`, `single-*.php`, `header.php`, `footer.php`, and `index.php` to be in the root directory. WordPress won't find them elsewhere.

---

## 📦 ASSET MANAGEMENT

### How Assets Are Loaded

#### CSS Loading (via `inc/enqueue.php`)
- **Base System**: Always loaded on all pages
  - `base.css` (design tokens + resets)
  - `layout.css` (grids, containers)
  - `buttons.css` (shim, empty)
  - `components.css` (reusable components)
  - `utilities.css` (helper classes)

- **Marketing Pages** (Home, Pricing, Early Access):
  - `sections.css` (homepage sections: hero, FAQ, CTA, etc.)
  - Page-specific CSS (`home.css`, `pricing.css`, `early-access.css`)

- **Standard Pages & Blog** (generic `page.php`, blog archive, single post):
  - `layout.css` (containers, section spacing — ensures `.jcp-container` works on standard pages)
  - `sections.css` (when loading blog/single/page styles)
  - `blog.css` (single post, archive, comments, post cards)

- **Feature Pages** (Demo, Directory, Estimate):
  - `demo.css` (imports `assets/shared/assets/demo.css`)
  - `directory-consolidated.css` (imports `assets/directory/directory.css` + `assets/shared/assets/demo.css`)
  - `estimate.css` (imports `assets/estimate/estimate-builder.css`)

#### JavaScript Loading (via `inc/enqueue.php`)
- **Always Loaded:**
  - `js/core/jcp-nav.js` (global navigation)
  - `js/core/jcp-render.js` (template loader)

- **Page-Specific:**
  - Homepage: `js/pages/home.js`
  - Pricing: `js/pages/pricing.js` + `js/features/faq.js`
  - Early Access: `js/pages/early-access.js`
  - Demo: `js/features/demo/jcp-demo.js` + Leaflet library
  - Directory: `js/features/directory/directory.js`
  - Estimate: `js/features/estimate/*.js` (3 files)
  - Survey: `js/features/survey/survey.js`

#### HTML Template Loading (via `jcp-render.js`)
- Demo page (`/demo?mode=run`): `assets/demo/index.html`
- Survey mode (`/demo` without `mode=run`): `assets/survey/index.html`
- Directory page (`/directory`): `assets/directory/index.html`
- Company profile (`/company/[slug]`): `assets/directory/profile.html`
- Estimate page (`/estimate`): `assets/estimate/index.html`

**Note:** HTML templates have their `<script>` and `<link>` tags stripped by `jcp-render.js` and replaced by WordPress enqueues.

### Asset Helper Functions

Located in `inc/helpers.php`:

```php
jcp_core_asset_path($relative_path)    // Get file path
jcp_core_asset_url($relative_path)     // Get file URL
jcp_core_enqueue_style($handle, $path, $deps)  // Enqueue CSS
jcp_core_enqueue_script($handle, $path, $deps) // Enqueue JS
```

**Path Resolution:**
1. Check theme root for file
2. If not found, check `assets/` folder
3. Cache-busting via `filemtime()`

---

## 🎨 TEMPLATE SYSTEM

### WordPress Template Hierarchy

WordPress looks for templates in this order:
1. `page-{slug}.php` (e.g., `page-pricing.php`)
2. `page-{id}.php` (e.g., `page-123.php`)
3. `page.php` (generic page template)
4. `index.php` (fallback)

**Our theme uses:** `page-{slug}.php` files for all pages.

### Page Detection

Located in `inc/helpers.php::jcp_core_get_page_detection()`:

```php
$pages = [
    'is_home'         => is_front_page() || $path === '' || $path === 'home',
    'is_pricing'      => is_page_template('page-pricing.php') || $path === 'pricing',
    'is_early_access' => is_page_template('page-early-access.php') || $path === 'early-access',
    'is_demo'         => is_page_template('page-demo.php') || $path === 'demo',
    'is_directory'    => is_page_template('page-directory.php') || $path === 'directory',
    'is_estimate'     => is_page_template('page-estimate.php') || $path === 'estimate',
    'is_company'      => is_singular('jcp_company') || $path === 'company',
    'is_design_system' => is_page_template('page-design-system.php') || $path === 'design-system',
    'is_ui_library'   => is_page_template('page-ui-library.php') || $path === 'ui-library',
];
```

### Template Routing

Located in `inc/template-routes.php`:
- Handles 404 fallbacks for routes like `/demo`, `/pricing`, `/directory`
- Maps URLs to template files even if WordPress pages don't exist
- Allows SPA-style routing

---

## 💻 DEVELOPMENT GUIDELINES

### CSS Development Rules

#### ✅ DO:
- Use CSS variables from `tokens.css` (e.g., `var(--jcp-color-primary)`)
- Use spacing scale (e.g., `var(--jcp-space-lg)` = 24px)
- Put reusable components in `components.css`
- Put homepage sections in `sections.css`
- Keep page-specific CSS minimal (< 200 lines ideally)

#### ❌ DON'T:
- Hardcode colors (use `var(--jcp-color-*)`)
- Hardcode spacing (use `var(--jcp-space-*)`)
- Put reusable styles in page-specific CSS
- Create page-specific component variants
- Use inline styles

### JavaScript Development Rules

#### ✅ DO:
- Put global behavior in `js/core/`
- Put feature-specific code in `js/features/{feature}/`
- Put page-specific renderers in `js/pages/`
- Use `window.JCP_ASSET_BASE` for asset paths
- Follow existing patterns (string templates vs HTML loading)

#### ❌ DON'T:
- Mix concerns (core vs features vs pages)
- Hardcode asset paths
- Create new rendering patterns without documenting

### Template Development Rules

#### ✅ DO:
- Keep WordPress-required files in root (`page-*.php`, `header.php`, `footer.php`)
- Use `get_template_part()` for reusable components
- Use `templates/global/` for header/footer/nav
- Keep templates minimal (delegate to JS or HTML files)

#### ❌ DON'T:
- Move WordPress-required files out of root
- Duplicate header/footer logic
- Mix PHP rendering with JS rendering unnecessarily

### Adding New Pages

1. **Create WordPress template** (`page-{slug}.php`):
   ```php
   <?php get_header(); ?>
   <div id="jcp-app" data-jcp-page="{slug}"></div>
   <?php get_footer(); ?>
   ```

2. **Add page detection** in `inc/helpers.php`:
   ```php
   'is_{slug}' => is_page_template('page-{slug}.php') || $path === '{slug}',
   ```

3. **Add enqueue logic** in `inc/enqueue.php`:
   ```php
   if ( $pages['is_{slug}'] ) {
       jcp_core_enqueue_style('jcp-core-{slug}', 'css/pages/{slug}.css', [...]);
       jcp_core_enqueue_script('jcp-core-{slug}', 'js/pages/{slug}.js', [...]);
   }
   ```

4. **Create renderer** (`js/pages/{slug}.js` or HTML template in `assets/{slug}/index.html`)

5. **Update `jcp-render.js`** if using HTML template pattern

---

## ✅ CURRENT STATUS

### Completed Refactoring Phases

#### ✅ Phase C - Stage 1C: Button Deduplication
- All button styles consolidated into `components.css`
- `buttons.css` converted to empty shim
- Zero visual changes, exact cascade preserved

#### ✅ Phase C - Stage 2: Homepage Section CSS Consolidation
- All homepage section styles moved to `sections.css`
- `home.css` now only contains page-specific overrides
- Sections can be reused across pages (FAQ, Final CTA)

#### ✅ Phase C - Stage 2B: Flattened Sections
- `sections.css` is now a single physical file (no `@import`)
- All section CSS inlined for better performance

#### ✅ Phase C - Stage 2C: Section Decommissioning
- All `css/pages/home/*.css` files converted to stubs
- Styles now live only in `sections.css`

#### ✅ Phase C - Stage 2D: Shared Section Rebinding
- Pricing and Early Access pages now use `sections.css` for FAQ/CTA
- No duplicate CSS, clean dependency chain

#### ✅ Phase C - Stage 3: JavaScript Organization
- All JS moved to `assets/js/` with clear structure:
  - `core/` - Global behavior
  - `features/` - Feature-specific modules
  - `pages/` - Page-specific renderers
- All enqueue paths updated

#### ✅ Phase C - Stage 4: Legacy Asset Decommissioning
- Empty folders removed (`assets/core/`, `assets/css/`)
- Legacy CSS files archived to `archive/legacy/css/`
- Legacy JS files archived to `archive/legacy/js/`

#### ✅ Template Cleanup
- Deleted `front-page.php` (duplicate)
- Deleted `templates/pages/home.php` (unused)
- Deleted `templates/sections/` (unused)
- Deleted `templates/components/` (unused)
- Deleted unused partials
- Removed empty folders

#### ✅ Assets Cleanup
- Deleted `assets/shared/assets/marketing.css` (unused)
- Fixed Leaflet library location
- Removed broken JS references from HTML templates
- Cleaned up CDN references

#### ✅ Directory & Profile Page Refactoring
- Unified directory and profile pages with global design system
- Removed "box within box" styling issues
- Consolidated badge styles (removed borders, added unlisted variant)
- Improved hero layouts with gallery components
- Standardized CTA components across all pages
- Fixed navigation consistency across directory and profile pages

#### ✅ Badge System Updates
- Removed borders from all badge variants (verified, trusted, listed, unlisted)
- Added "Unlisted" badge variant (grey styling)
- Updated badge filtering logic to exclude unlisted badges when "verified only" is active
- Standardized badge appearance across directory listings and profile pages

#### ✅ Code Organization & Cleanup
- Removed audit documentation files (consolidated into main docs)
- Updated README and DOCUMENTATION with current project state
- Cleaned up unused CSS files and empty stubs
- Organized all JavaScript into clear feature/page structure

#### ✅ WordPress readiness & template improvements (Jan 2026)
- **Theme identity:** `style.css` — Text Domain `jcp-core`, Theme URI, Description; `functions.php` — `load_theme_textdomain()` for translations.
- **Page templates:** All custom page templates have `Template Name:` and docblocks (Home, Pricing, Early Access, Demo, Directory, Estimate, Company, Design System, UI Library) so they appear in Page Attributes.
- **Standard page / blog / single:** `page.php`, `home.php`, `single.php` use a single `<section>` so content is in one container with one block of padding (no double gap under headlines). Standard pages and blog load `layout.css` and (when needed) `sections.css` via `inc/enqueue.php`.
- **Single post:** Author with round avatar in meta; one horizontal rule before comments; compact comment section (smaller form, tighter list). `comments.php` textarea 4 rows; `blog.css` comment and post-nav spacing updated.
- **Escaping & i18n:** Archive dates escaped; “Read more”, “Tags:”, footer logo alt/text; comments “One Comment” fix; `page-ui-library.php` icon helper returns `esc_url()` + `sanitize_file_name()`; single post nav strings translatable.
- **Pricing content:** Plans and feature lists in `assets/js/pages/pricing.js` (Starter $99, Scale $249, Enterprise $399); additional pricing notes in pricing section; Enterprise+ removed.

### Current File Counts

| Category | Count | Status |
|----------|-------|--------|
| **Root PHP Files** | 14 | ✅ All required by WordPress |
| **Template Files** | 4 | ✅ All used |
| **CSS Files** | 15 | ✅ All used |
| **JavaScript Files** | 15 | ✅ All organized |
| **HTML Templates** | 5 | ✅ All used |
| **Unused Files** | 0 | ✅ All cleaned up |

---

## 📚 QUICK REFERENCE

### CSS Variables (Design Tokens)

**Colors:**
- `--jcp-color-primary` (#ff5036)
- `--jcp-color-secondary` (#1f2937)
- `--jcp-color-text-primary` (#111827)
- `--jcp-color-bg-primary` (#ffffff)
- `--jcp-color-border` (#e5e7eb)

**Spacing (8px scale):**
- `--jcp-space-xs` (4px)
- `--jcp-space-sm` (8px)
- `--jcp-space-md` (16px)
- `--jcp-space-lg` (24px)
- `--jcp-space-xl` (32px)
- `--jcp-space-2xl` (40px)
- `--jcp-space-3xl` (48px)
- `--jcp-space-4xl` (56px)
- `--jcp-space-5xl` (64px)
- `--jcp-space-6xl` (80px)

**Typography:**
- `--jcp-font-size-base` (16px)
- `--jcp-font-size-lg` (18px)
- `--jcp-font-size-xl` (20px)
- `--jcp-font-size-2xl` (24px)
- `--jcp-font-size-3xl` (30px)
- `--jcp-font-size-4xl` (36px)
- `--jcp-font-size-5xl` (48px)
- `--jcp-font-size-6xl` (60px)

**Full list:** See `css/tokens.css` or `css/base.css`

### Common CSS Classes

**Layout:**
- `.jcp-container` - Standard container (1240px max, 94% responsive)
- `.jcp-section` - Full-width section wrapper
- `.jcp-grid-2` - Two-column grid
- `.jcp-grid-3` - Three-column grid

**Components:**
- `.btn.btn-primary` - Primary button
- `.btn.btn-secondary` - Secondary button
- `.jcp-card` - Card component
- `.directory-badge` - Directory listing badges (verified, trusted, listed, unlisted)
- `.rankings-cta` - Standard CTA section (orange background, white text)

**Badge Variants:**
- `.directory-badge.verified` - Blue background, blue text (no border)
- `.directory-badge.trusted` - Orange background, brown text (no border)
- `.directory-badge.listed` - Light grey background, grey text (no border)
- `.directory-badge.unlisted` - Medium grey background, grey text (no border)

**Utilities:**
- `.jcp-text-center` - Center-aligned text
- `.jcp-text-muted` - Muted text color

### JavaScript Global Variables

- `window.JCP_ASSET_BASE` - Base URL for assets
- `window.JCP_CONFIG.baseUrl` - Site base URL
- `window.JCP_DIRECTORY_DATA` - Directory listings data
- `window.JCP_PROFILE_DATA` - Company profile data

### Page URLs & Templates

| URL | Template | Rendering Method |
|-----|----------|------------------|
| `/` or `/home` | `page-home.php` | JS string templates |
| `/pricing` | `page-pricing.php` | JS string templates |
| `/early-access` | `page-early-access.php` | JS string templates |
| `/demo?mode=run` | `page-demo.php` | HTML template (`assets/demo/index.html`) |
| `/demo` | `page-demo.php` | HTML template (`assets/survey/index.html`) |
| `/directory` | `page-directory.php` | HTML template (`assets/directory/index.html`) |
| `/company/[slug]` | `single-jcp_company.php` | HTML template (`assets/directory/profile.html`) |
| `/estimate` | `page-estimate.php` | HTML template (`assets/estimate/index.html`) |
| `/design-system` | `page-design-system.php` | PHP template |
| `/ui-library` | `page-ui-library.php` | PHP template |
| Generic page (e.g. Sample page) | `page.php` | PHP template (one section, one container) |
| Blog archive | `home.php` | PHP template (one section, post grid) |
| Single post | `single.php` | PHP template (one section, author meta, comments) |

---

## 🔌 SETUP & INTEGRATIONS

### Local Development

- Run WordPress locally (e.g. [Local](https://localwp.com/) by Flywheel, MAMP, or similar).
- Point the site at the theme directory; ensure PHP and MySQL meet WordPress requirements.
- For GHL webhook testing, use a tunnel (e.g. ngrok) so GoHighLevel can reach your local REST endpoints, or test on a staging URL.

### GoHighLevel Webhooks

The theme posts form submissions to **two separate** GoHighLevel inbound webhooks. Do not mix them.

| Form | Purpose | Webhook URL constant | File |
|------|---------|----------------------|------|
| **Early Access** | Founding crew signup → Early Access automation | `JCP_GHL_WEBHOOK_URL_DEFAULT` | `inc/rest-early-access.php` |
| **Demo Survey** | Demo signup → Demo Follow-Up automation | `JCP_GHL_DEMO_SURVEY_WEBHOOK_URL` | `inc/rest-demo-survey.php` |

- **Early Access:** REST route `POST /wp-json/jcp/v1/early-access-submit`; payload is `application/x-www-form-urlencoded` with contact + referral fields.
- **Demo Survey:** REST route `POST /wp-json/jcp/v1/demo-survey-submit`; payload includes contact + demo-specific fields and tags (e.g. `demo-completed`, `demo-interest`). Do not apply early-access tags from the Demo Survey.

### ACF (Advanced Custom Fields)

- **Homepage Settings:** ACF is required for the **Homepage** options page (hero, how it works, FAQ, pricing, features, footer, section visibility). All homepage content is driven from ACF options.
- **Early Access:** Has **no** backend options in this theme. Form behavior (required fields, success redirect, options lists) is hardcoded in `inc/acf-config.php` and `inc/rest-early-access.php`.

### Debug Logging

- GHL webhook requests/responses are logged with `error_log()` only when `WP_DEBUG_LOG` is defined and true (see `inc/rest-early-access.php`). Disable in production or ensure logs are not exposed.

---

## 🔧 MAINTENANCE

### Updating This Documentation

**When to Update:**
- After adding new pages/templates
- After restructuring CSS/JS
- After adding new features
- After cleanup/refactoring
- When file counts change

**How to Update:**
1. Edit `DOCUMENTATION.md` directly
2. Update relevant sections
3. Update "Last Updated" date
4. Commit changes

### File Organization Rules

1. **WordPress-required files MUST stay in root** (`page-*.php`, `header.php`, `footer.php`, `index.php`)
2. **CSS follows cascade order** (tokens → base → layout → components → sections → utilities → pages)
3. **JS follows structure** (core → features → pages)
4. **Templates are minimal** (delegate to JS or HTML files)
5. **No duplicate files** (one source of truth per component)

### Cleanup Checklist

Before committing:
- [ ] No unused files
- [ ] No duplicate files
- [ ] All files documented
- [ ] Documentation updated
- [ ] Visual parity confirmed

---

## 📞 SUPPORT

### Common Issues

**Q: Why are HTML files in `assets/` folder?**
A: They're loaded client-side via AJAX by `jcp-render.js`. This is an unusual pattern but it's how the theme works.

**Q: Why can't I move `page-*.php` files to `templates/`?**
A: WordPress template hierarchy requires them in root. WordPress won't find them elsewhere.

**Q: Where do I add new CSS?**
A: 
- Reusable components → `components.css`
- Homepage sections → `sections.css`
- Page-specific → `css/pages/{page}.css`

**Q: Where do I add new JavaScript?**
A:
- Global behavior → `js/core/`
- Feature-specific → `js/features/{feature}/`
- Page-specific → `js/pages/`

**Q: How do I add a new page?**
A: See "Adding New Pages" section above.

---

**Last Updated:** January 28, 2026  
**Version:** 1.2  
**Maintained By:** Development Team  
**Questions?** Refer to this documentation first, then consult codebase.
