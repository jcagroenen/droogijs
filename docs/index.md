# Droogijs Documentation

## Current State

- Multisite configured and working
- Brand color system implemented (Tailwind v4)
- Homepage built: hero, features, use-cases, cta sections
- Navigation header with brand-specific animated logos
- View composers serving brand-specific content
- Blade Icons installed for SVG icon management
- Inspiratie (blog) page with dynamic post grid
- Use-cases section pulls from actual blog posts

## Next Steps

- Mobile menu functionality (JS)
- Footer section
- WooCommerce installation & templates
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
