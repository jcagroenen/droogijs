# WooCommerce Integration

## Overview

WooCommerce templates with modular, Magento-like structure. Brand-specific headers, trust badges, and info sections.

## Setup

### Installation

WooCommerce plugin installed in root (Bedrock), sage-woocommerce in theme:

```bash
# Root - WooCommerce plugin
composer require wpackagist-plugin/woocommerce

# Theme - Blade template support
cd web/app/themes/droogijs
composer require generoi/sage-woocommerce
wp acorn vendor:publish --tag='sage-woocommerce-views'
```

### WooCommerce Configuration

1. Go to **WooCommerce → Settings → Products**
2. Set **Shop page** to your shop page
3. Configure shipping, payments per site

## Template Structure

```
woocommerce/
├── archive-product.blade.php      # Shop page
├── content-product.blade.php      # Product loop wrapper
├── single-product.blade.php       # Single product page
│
├── catalog_view/                  # Shop page partials
│   ├── shop-header.blade.php      # Header with brand title
│   ├── trust-badges.blade.php     # Trust indicators
│   └── shop-info.blade.php        # Info blocks below grid
│
├── product_card/                  # Product card partials
│   ├── card.blade.php             # Main card wrapper
│   ├── image.blade.php            # Product image + badges
│   ├── info.blade.php             # Title, description, delivery
│   └── actions.blade.php          # Price + add to cart
│
└── product_view/                  # Single product partials
    ├── product-header.blade.php   # Breadcrumb area
    ├── gallery.blade.php          # Image gallery (Alpine.js)
    ├── product-title.blade.php    # Product title
    ├── short-description.blade.php
    ├── pricing.blade.php          # Price display
    ├── stock-status.blade.php     # Stock indicator
    ├── add-to-cart.blade.php      # Add to cart form
    ├── delivery-info.blade.php    # Shipping info box
    ├── description.blade.php      # Full description
    └── attributes.blade.php       # Product specifications
```

## Brand-Specific Content

### Shop Header (`catalog_view/shop-header.blade.php`)

Displays brand-specific titles and descriptions:

| Brand | Title | Description |
|-------|-------|-------------|
| thuis | Droogijs voor Thuis | Consumer-focused messaging |
| horeca | Droogijs voor Horeca | B2B hospitality messaging |
| industrie | Droogijs voor Industrie | Industrial messaging |

### Trust Badges (`catalog_view/trust-badges.blade.php`)

| Brand | Badges |
|-------|--------|
| thuis | Gratis verzending, Veilig verpakt, Instructies |
| horeca | 24 uur levering, HACCP, Volume kortingen |
| industrie | Op maat levering, ISO, Industriële kwaliteit |

### Info Section (`catalog_view/shop-info.blade.php`)

Three info blocks per brand with relevant messaging for each audience.

## Product Card

The product card shows:

- Product image (or branded fallback)
- Sale/stock badges
- Title + short description
- Delivery time (from `levertijd` attribute)
- Quality guarantee badge
- Price
- Add to cart button

## Single Product Page

Two-column layout:
- **Left**: Image gallery with Alpine.js thumbnails
- **Right**: Title, description, stock, price, add to cart, delivery info

Full-width below:
- Product description
- Specifications (from product attributes)
- Related products

## Product Attributes

Create these WooCommerce attributes for full functionality:

| Attribute | Slug | Purpose |
|-----------|------|---------|
| Levertijd | `levertijd` | Delivery time display (e.g., "24 uur") |

## URLs

- `/shop/` - Shop page (WooCommerce archive)
- `/product/product-slug/` - Single product

## Customization

To override a partial for specific brands, you can add brand checks:

```blade
@php
  $brand = App\get_current_brand();
@endphp

@if($brand === 'industrie')
  {{-- Custom industrie layout --}}
@else
  {{-- Default layout --}}
@endif
```
