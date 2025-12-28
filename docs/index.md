# Droogijs Documentation

## Work In Progress (WIP)

**Last session:** Deployment, mobile menu & shop fixes

**What we did:**
- Deployed multisite to Ploi with Deployer
- Fixed multisite installation issues (redirect loops, wp-signup.php)
- Configured SSL for subdomains
- Set up DNS wildcard for subdomains
- Created staging sites (thuis/horeca/industrie)
- Added dev/staging site switcher in header
- Network activated WooCommerce
- Installed WordPress Importer plugin for XML imports
- Updated hero CTA button to link to shop
- Added hero image (Droogijs_gaslas.jpg)
- Implemented mobile menu with Alpine.js
- Fixed shop page layout (sorting bar, product grid)

**Technical notes:**
- Alpine.js is self-hosted in `resources/scripts/alpine.js` (not bundled via Vite due to multisite CORS issues)
- Alpine is enqueued via WordPress in `app/setup.php`
- Use `x-on:click` instead of `@click` in Blade templates (Blade interprets `@` as directive)

**Where we left off:**
- Staging is live at `droogijs.groenen-webdev.nl`
- All three subsites working with SSL
- Site switcher for easy testing between brands
- Hero section complete with image
- Mobile menu working
- Shop page grid fixed

**Ready to continue with:**
- Add products to horeca/industrie sites
- Style cart & checkout templates
- Footer section
- Test mobile menu on staging (deploy needed)

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
