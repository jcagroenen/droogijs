# Droogijs Multi-Store Architecture

## Overview

Three separate WooCommerce stores running on WordPress Multisite, sharing one codebase and admin.

## Domains

| Site | Domain | Target Audience | Color Theme |
|------|--------|-----------------|-------------|
| Thuis | droogijsvoorthuis.nl | B2C - Consumers | Cyan (#0891b2) |
| Horeca | droogijsvoorhoreca.nl | B2B - Hospitality | Amber (#d97706) |
| Industrie | droogijsvoorindustrie.nl | B2B - Industrial | Slate (#334155) |

## Tech Stack

- **WordPress**: Multisite with domain mapping
- **Framework**: Bedrock (Roots)
- **Theme**: Sage 10 (single theme, multi-brand)
- **E-commerce**: WooCommerce (separate install per site)
- **CSS**: Tailwind CSS
- **Icons**: Lucide (or Blade Icons equivalent)

## Architecture Decisions

### One Theme, Three Brands

The Sage theme detects which site it's running on and applies:
- Appropriate color scheme (CSS custom properties)
- Site-specific content/copy
- Shared components and layouts

Detection method:
```php
// In theme setup or helper
function get_current_brand(): string {
    $site_id = get_current_blog_id();

    return match($site_id) {
        1 => 'thuis',
        2 => 'horeca',
        3 => 'industrie',
        default => 'thuis'
    };
}
```

### Product Management

- Products are NOT synced between stores
- Each store manages its own catalog, pricing, inventory
- Same physical product = separate WooCommerce product per store
- Allows different pricing per audience (B2C vs B2B)

### Site Switcher

Navigation includes a dropdown to switch between the three domains.
This links to external domains (not internal multisite switching).

## Directory Structure

```
/Volumes/droogijs/
├── config/                 # Bedrock config
├── docs/                   # Project documentation
├── examples/               # Original React designs (reference)
├── web/
│   ├── app/
│   │   ├── themes/
│   │   │   └── droogijs/   # Sage theme
│   │   ├── plugins/
│   │   └── mu-plugins/
│   └── wp/                 # WordPress core (gitignored)
├── composer.json
└── .env                    # Environment config (gitignored)
```

## Setup Checklist

- [ ] Install Sage theme
- [ ] Configure Tailwind with brand colors
- [ ] Enable WordPress Multisite
- [ ] Create 3 network sites
- [ ] Configure domain mapping
- [ ] Install WooCommerce on each site
- [ ] Convert React components to Blade
- [ ] Build WooCommerce templates

## Component Mapping (React → Blade)

| React Component | Blade Partial |
|----------------|---------------|
| Navigation.tsx | partials/navigation.blade.php |
| Hero.tsx | partials/hero.blade.php |
| Features.tsx | partials/features.blade.php |
| UseCases.tsx | partials/use-cases.blade.php |
| CTA.tsx | partials/cta.blade.php |
| Footer.tsx | partials/footer.blade.php |

## Notes

- Original designs in `/examples` are React + Vite + Tailwind
- Theme prop pattern converts to brand detection in PHP
- Tailwind classes remain largely the same
