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
- `DOCUMENTATION.md` - Complete technical documentation

### Development

1. **CSS Development:** Use design tokens from `css/base.css`, add reusable components to `css/components.css`, page-specific styles to `css/pages/`
2. **JavaScript Development:** Core behavior in `assets/js/core/`, features in `assets/js/features/`, page renderers in `assets/js/pages/`
3. **Template Development:** WordPress-required files stay in root, reusable parts in `templates/`

### Current Status

✅ **All refactoring complete** - Clean, organized structure  
✅ **No duplicate files** - Single source of truth for all components  
✅ **WordPress-ready** - Template Name headers, text domain, escaping, layout for standard pages  
✅ **Blog & single post** - Single-section layout (no double gaps), author + avatar in meta, compact comments  
✅ **Fully documented** - See DOCUMENTATION.md for details

For detailed information, see [DOCUMENTATION.md](./DOCUMENTATION.md).
