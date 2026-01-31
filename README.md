# JobCapturePro Core Theme

WordPress theme for JobCapturePro public website, directory, and estimator.

## 📚 Documentation

**👉 See [DOCUMENTATION.md](./DOCUMENTATION.md) for complete documentation**

The master documentation includes:
- Project overview and architecture
- Complete file organization
- Asset management guide
- Template system explanation
- Development guidelines
- Quick reference

## Quick Start

### Theme Structure

- **Theme Root:** `/wp-content/themes/jobcapturepro-core/`
- **CSS:** `/css/` (organized: base → layout → components → sections → utilities → pages)
- **JavaScript:** `/assets/js/` (organized: core → features → pages)
- **Templates:** Root PHP files + `/templates/global/` for header/footer/nav
- **Assets:** `/assets/` (HTML templates, icons, images, third-party libraries)

### Key Files

- `functions.php` - Theme bootstrap and initialization
- `inc/enqueue.php` - Asset loading logic
- `inc/helpers.php` - Utility functions and page detection
- `inc/template-routes.php` - URL routing and 404 handling
- `inc/form-fields.php` - Canonical REST param names and GHL payload keys (Demo Survey = source of truth)
- `inc/rest-early-access.php` - Early Access form → GHL webhook
- `inc/rest-demo-survey.php` - Demo Survey form → GHL webhook (separate from Early Access)
- `DOCUMENTATION.md` - Complete technical documentation

### Forms & GoHighLevel

Two critical forms submit to GoHighLevel via separate webhooks:

| Form | Purpose | REST endpoint |
|------|---------|---------------|
| **Early Access** | Founding crew signup (contact, business type, why interested, referral). One submit → one webhook. | `POST /wp-json/jcp/v1/early-access-submit` |
| **Demo Survey** | Demo opt-in ("Continue to preview") and viewed-demo ("Skip" / "Launch"). Same webhook; GHL branches on Event (demo-opt-in vs demo-viewed). | `POST /wp-json/jcp/v1/demo-survey-submit`, `POST /wp-json/jcp/v1/demo-viewed-submit` |

- **Data flow:** Frontend → REST (validate, build body) → `wp_remote_post()` to form’s GHL webhook. Payloads are `application/x-www-form-urlencoded`, flat key-value. Mapping to contact/custom fields is done in GHL workflows.
- **Field naming:** Shared concepts (first_name, email, company, business_type) use consistent REST params; GHL payload keys are documented in DOCUMENTATION.md → Forms & GoHighLevel. Do not change webhook payload keys without updating GHL.

### Setup & Integrations

- **Local:** Run WordPress locally (e.g. Local by Flywheel). Use a tunnel for GHL webhook testing if needed.
- **GoHighLevel:** Two webhooks—Early Access (`rest-early-access.php`) and Demo Survey (`rest-demo-survey.php`). Do not swap URLs; see DOCUMENTATION.md → Setup & Integrations and Forms & GoHighLevel.
- **ACF:** Required for Homepage Settings, **JCP Theme Settings** (global CTAs, form copy, footer basics), and **per-page bottom CTA** on Pages. Early Access and Demo Survey copy can be overridden via Theme Settings.

### Development

1. **CSS Development:** Use design tokens from `css/base.css`, add reusable components to `css/components.css`, page-specific styles to `css/pages/`
2. **JavaScript Development:** Core behavior in `assets/js/core/`, features in `assets/js/features/`, page renderers in `assets/js/pages/`
3. **Template Development:** WordPress-required files stay in root, reusable parts in `templates/`

### Theme Settings (ACF Options)

**JCP Theme Settings** (WP Admin → JCP Theme Settings) controls:

- **Global CTAs:** Primary and secondary CTA labels and URLs (used in nav and CTA blocks).
- **Early Access Form Copy:** Headline, subhead, button label, success message.
- **Demo Survey Copy:** Headline, subhead, button label (Step 1), success message.
- **Footer Basics:** Support email, sales email, optional address line (shown in footer when set).

### Per-Page Bottom CTA

On any **Page** (post type `page`), editors can enable an optional **Bottom CTA** that appears at the end of the page content. It uses the same global CTA component (`.rankings-cta`) and classes—no new design or CSS.

- **Where:** Bottom of the page, after the main content (standard `page.php` template only).
- **How to enable:** Edit the page → **Bottom CTA** meta box → turn on “Enable bottom CTA” and fill headline, supporting text (optional), button label, and button URL.
- **Behavior:** If disabled or required fields (headline, button label, URL) are empty, nothing is output—no empty spacing.

### Current Status

✅ **All refactoring complete** - Clean, organized structure  
✅ **No duplicate files** - Single source of truth for all components  
✅ **WordPress-ready** - Template Name headers, text domain, escaping, layout for standard pages  
✅ **Blog & single post** - Single-section layout (no double gaps), author + avatar in meta, compact comments  
✅ **Real Job Proof section** - Homepage section under "How JobCapturePro works"; shows proof outputs (Google, Website, Directory, Reviews) and reinforces the verified directory. See DOCUMENTATION.md.  
✅ **Fully documented** - See DOCUMENTATION.md for details

For detailed information, see [DOCUMENTATION.md](./DOCUMENTATION.md).
