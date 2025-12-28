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
| wpackagist-plugin/wordpress-importer | Root | XML import support |
| generoi/sage-woocommerce | Theme | Blade template support |
| blade-ui-kit/blade-icons | Theme | SVG icon management |

## Static Assets (Images)

Images in `resources/images/` are processed by Vite. Use in Blade templates:

```php
<img src="{{ Vite::asset('resources/images/filename.jpg') }}" alt="...">
```

Note: `@asset()` doesn't work for images in Sage 11 - use `Vite::asset()` instead.

## Alpine.js Setup

Alpine.js is **self-hosted** (not bundled via Vite) due to CORS issues with multisite subdomains during development.

**Location:** `resources/scripts/alpine.js`

**Enqueued in:** `app/setup.php`

```php
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('alpine-js', get_template_directory_uri() . '/resources/scripts/alpine.js', [], null, ['in_footer' => false, 'strategy' => 'defer']);
});
```

**Important:** In Blade templates, use `x-on:click` instead of `@click` because Blade interprets `@` as a directive.

```blade
{{-- Wrong - Blade will try to parse @click as directive --}}
<button @click="open = !open">

{{-- Correct --}}
<button x-on:click="open = !open">
```
