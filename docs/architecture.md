# Architecture Overview

## Concept

Three WooCommerce stores on WordPress Multisite, one Sage theme with dynamic branding.

## Tech Stack

- Bedrock (WordPress boilerplate)
- Sage 11 (theme)
- Tailwind CSS v4
- WooCommerce (per-site)

## Directory Structure

```
droogijs/
├── config/              # Bedrock config (multisite settings)
├── docs/                # This documentation
├── web/
│   └── app/
│       └── themes/
│           └── droogijs/
│               ├── app/
│               │   ├── filters.php         # Brand detection
│               │   └── View/Composers/     # View composers
│               └── resources/
│                   ├── css/app.css         # Brand colors
│                   └── views/
│                       ├── front-page.blade.php
│                       ├── layouts/
│                       ├── partials/
│                       └── sections/
```

## Domains (Production)

| Domain | Audience | Brand |
|--------|----------|-------|
| droogijsvoorthuis.nl | B2C Consumers | thuis |
| droogijsvoorhoreca.nl | B2B Hospitality | horeca |
| droogijsvoorindustrie.nl | B2B Industrial | industrie |

## Products

Not synced between stores. Each store has its own catalog and pricing.
