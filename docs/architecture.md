# Architecture Overview

## Concept

Three WooCommerce stores on WordPress Multisite, one Sage theme with dynamic branding.

## Tech Stack

- Bedrock (WordPress boilerplate)
- Sage 11 (theme)
- Acorn 5 (Laravel components for Sage)
- Tailwind CSS v4
- Blade Icons (SVG icon management)
- WooCommerce (per-site)

## Directory Structure

```
droogijs/
├── config/                  # Bedrock config (multisite settings)
├── docs/                    # This documentation
├── web/
│   └── app/
│       └── themes/
│           └── droogijs/
│               ├── app/
│               │   ├── filters.php             # Brand detection
│               │   └── View/Composers/
│               │       ├── FrontPage.php       # Homepage data
│               │       └── Inspiratie.php      # Blog page data
│               ├── config/
│               │   └── blade-icons.php         # Icon config
│               └── resources/
│                   ├── css/app.css             # Brand colors + utilities
│                   ├── icons/                  # SVG icons
│                   └── views/
│                       ├── front-page.blade.php
│                       ├── template-inspiratie.blade.php
│                       ├── layouts/
│                       ├── partials/
│                       │   ├── logo.blade.php      # Brand logos
│                       │   ├── blog-card.blade.php # Blog card component
│                       │   ├── hero.blade.php
│                       │   ├── features.blade.php
│                       │   ├── use-cases.blade.php
│                       │   └── cta.blade.php
│                       ├── sections/
│                       │   └── header.blade.php
│                       └── woocommerce/
│                           ├── archive-product.blade.php
│                           ├── single-product.blade.php
│                           ├── content-product.blade.php
│                           ├── catalog_view/       # Shop page partials
│                           ├── product_card/       # Product card partials
│                           └── product_view/       # Single product partials
```

## Domains (Production)

| Domain | Audience | Brand |
|--------|----------|-------|
| droogijsvoorthuis.nl | B2C Consumers | thuis |
| droogijsvoorhoreca.nl | B2B Hospitality | horeca |
| droogijsvoorindustrie.nl | B2B Industrial | industrie |

## Products

Not synced between stores. Each store has its own catalog and pricing.

## Composer Hierarchy

Two separate composer.json files:

```
droogijs/
├── composer.json           # Bedrock: WordPress core, plugins
└── web/app/themes/droogijs/
    └── composer.json       # Sage: Acorn, Blade Icons, theme deps
```

Run `composer require` from the appropriate directory.

## Package Locations

| Package | Location | Purpose |
|---------|----------|---------|
| wpackagist-plugin/woocommerce | Root | WooCommerce plugin |
| generoi/sage-woocommerce | Theme | Blade template support |
| blade-ui-kit/blade-icons | Theme | SVG icon management |
