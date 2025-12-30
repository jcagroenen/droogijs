# Droogijs Documentation

## Work In Progress (WIP)

**Last session:** Flat permalinks (Magento-style URLs)

**What we did:**
- Implemented flat URL structure (no `/product/` or `/product-category/` prefixes)
- Created `FlatPermalinks` class (~400 lines) handling URL rewriting and slug collision prevention
- Products now at `/product-slug/` instead of `/product/product-slug/`
- Categories now at `/category-slug/` instead of `/product-category/category-slug/`
- Old URLs automatically 301 redirect to new flat URLs
- Slug collision checker prevents products/categories from having same slug
- Production permalink locking in `app/filters.php`
- Full documentation in `docs/flat-permalinks.md`

**Technical notes:**
- Flat permalinks work by intercepting WordPress's `request` filter and `wp` action
- Template forcing required for Sage/Blade compatibility via `template_include` filter
- Settings locked in production via `pre_option_*` filters
- Required WP settings: Post name permalink structure (`/%postname%/`)

**Where we left off:**
- Flat URLs working on thuis.droogijs.test
- `/droogijs/` → Category page
- `/droogijs-dry-ice-6-kg-in-eps-doos/` → Product page
- Database backup in `branch_dbs/main/`

**Ready to continue with:**
- Apply flat permalinks to other multisite sites (horeca/industrie)
- Livewire filtering (in-place product filtering without page redirect)
- Footer section
- Cart & checkout template styling

## Current State

- Multisite configured and working
- Brand color system implemented (Tailwind v4)
- Homepage built: hero, features, use-cases, cta sections
- Navigation header with brand-specific animated logos
- View composers serving brand-specific content
- Blade Icons installed for SVG icon management
- Inspiratie (blog) page with dynamic post grid
- Use-cases section pulls from actual blog posts
- WooCommerce templates with modular structure
- Shop page with brand-specific headers and trust badges
- Single product page with gallery, pricing, add to cart

## Next Steps

- Configure WooCommerce shop page in WP Admin
- Add products to each site
- Mobile menu functionality (JS)
- Footer section
- Cart & checkout template styling
- Additional pages (contact, about, etc.)
- Single post template styling

## Quick Reference

| Doc | Description |
|-----|-------------|
| [architecture.md](architecture.md) | Project overview, tech stack, directory structure |
| [multisite.md](multisite.md) | WordPress multisite setup with Bedrock |
| [brand-colors.md](brand-colors.md) | Tailwind v4 dynamic color system |
| [view-composers.md](view-composers.md) | Sage view composers for brand content |
| [blade-icons.md](blade-icons.md) | Blade Icons setup and usage |
| [inspiratie.md](inspiratie.md) | Blog/Inspiratie page setup |
| [woocommerce.md](woocommerce.md) | WooCommerce templates and structure |
| [flat-permalinks.md](flat-permalinks.md) | Flat URL structure (no /product/ or /product-category/) |
| [delivery-date-picker.md](delivery-date-picker.md) | Flatpickr delivery date integration |
| [shipping-calendar-package.md](shipping-calendar-package.md) | Future: Extract to Composer package |
| [deployment.md](deployment.md) | Deploying multisite to Ploi (full guide) |
| [todo.md](todo.md) | Deployment tasks |

## Sites

| ID | Subdomain | Brand | Color |
|----|-----------|-------|-------|
| 1 | droogijs.test | thuis | Cyan |
| 2 | thuis.droogijs.test | thuis | Cyan |
| 3 | horeca.droogijs.test | horeca | Amber |
| 4 | industrie.droogijs.test | industrie | Slate |

## Key Files

```
app/filters.php              → get_current_brand() + body_class filter
resources/css/app.css        → Brand color system
app/View/Composers/          → View composers
resources/views/             → Blade templates
resources/icons/             → SVG icons (Blade Icons)
config/blade-icons.php       → Blade Icons config
partials/logo.blade.php      → Brand-specific animated logos
template-inspiratie.blade.php → Blog page template
```
