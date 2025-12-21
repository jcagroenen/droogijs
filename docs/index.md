# Droogijs Documentation

## Current State

- Multisite configured and working
- Brand color system implemented (Tailwind v4)
- Homepage built: hero, features, use-cases, cta sections
- Navigation header with brand-colored CTA button
- View composers serving brand-specific content

## Next Steps

- Mobile menu functionality (JS)
- Footer section
- WooCommerce installation & templates
- Additional pages (contact, about, etc.)

## Quick Reference

| Doc | Description |
|-----|-------------|
| [architecture.md](architecture.md) | Project overview, tech stack, directory structure |
| [multisite.md](multisite.md) | WordPress multisite setup with Bedrock |
| [brand-colors.md](brand-colors.md) | Tailwind v4 dynamic color system |
| [view-composers.md](view-composers.md) | Sage view composers for brand content |
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
app/filters.php          → get_current_brand() + body_class filter
resources/css/app.css    → Brand color system
app/View/Composers/      → View composers
resources/views/         → Blade templates
```
